<?php

namespace App\Repositories\Sales;

use App\Models\Sale;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Sales\Interfaces\MainSaleRepositoryInterface;
use App\Repositories\Sales\Interfaces\SalePaymentRepositoryInterface;
use App\Repositories\Sales\Interfaces\SaleProductRepositoryInterface;
use App\Repositories\Sales\Interfaces\SaleRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentMainSaleRepository
 * @package App\Repositories\Sales
 */
class EloquentMainSaleRepository implements MainSaleRepositoryInterface
{
    /**
     * @var SaleRepositoryInterface
     */
    protected $saleRepository;

    /**
     * @var SaleProductRepositoryInterface
     */
    protected $saleProductRepository;

    /**
     * @var SalePaymentRepositoryInterface
     */
    protected $salePaymentRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * EloquentMainSaleRepository constructor.
     * @param SaleRepositoryInterface $saleRepository
     * @param SaleProductRepositoryInterface $saleProductRepository
     * @param SalePaymentRepositoryInterface $salePaymentRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        SaleRepositoryInterface $saleRepository,
        SaleProductRepositoryInterface $saleProductRepository,
        SalePaymentRepositoryInterface $salePaymentRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->saleRepository = $saleRepository;
        $this->saleProductRepository = $saleProductRepository;
        $this->salePaymentRepository = $salePaymentRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $saleData
     * @param array $saleProducts
     * @param array $salePayments
     * @return array
     * @throws \Exception
     */
    public function create(array $saleData, array $saleProducts, array $salePayments): array
    {
        try {
            DB::beginTransaction();

            $saleData['prefix'] = 'FE';
            $saleData['consecutive'] = $this->getConsecutive();
            $saleData['status'] = 'Activa';
            $saleData['date'] = Carbon::now()->format('Y-m-d h:i:s');
            $saleSaved = $this->saleRepository->create($saleData);

            if (empty($saleSaved)) {
                throw new \Exception('Ha ocurrido un error almacenando la venta.', 500);
                DB::rollBack();
            }

            foreach ($saleProducts as $key => $saleProduct) {
                $saleProducts[$key]['sale_id'] = $saleSaved->id;
                $this->productRepository->updatePivotSubtractQuantity(
                    $saleProduct['product_id'],
                    $saleProduct['warehouse_id'],
                    $saleProduct['quantity']
                );
            }

            $saleProducts = $this->getSaleProductsWithRelationId($saleSaved->id, $saleProducts);
            $salePayments = $this->getSalePaymentsWithRelationId($saleSaved->id, $salePayments);

            $saleProductSaved = $this->saleProductRepository->create($saleProducts);
            $salePaymentSaved = $this->salePaymentRepository->create($salePayments);

            if (empty($saleProductSaved) || empty($salePaymentSaved)) {
                throw new \Exception('Ha ocurrido un error almacenando la venta.', 500);
                DB::rollBack();
            }

            DB::commit();

            return ['status' => true, 'message' => 'Venta registrada correctamente', 'code' => 201, 'sale' => $saleSaved];

        } catch (\Throwable $exception) {
            return ['status' => false, 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    /**
     * @param $saleId
     * @return array
     */
    public function cancel($saleId): array
    {
        try {
            DB::beginTransaction();

            $sale = $this->saleRepository->get($saleId);

            foreach ($sale['sale_products'] as $key => $saleProduct) {
               $response = $this->productRepository->updatePivotSumQuantity(
                    $saleProduct['product_id'],
                    $saleProduct['warehouse_id'],
                    $saleProduct['quantity']
                );

               if(!$response){
                   throw new \Exception('Ha ocurrido un error anulando la factura de venta.', 500);
                   DB::rollBack();
               }
            }
            $response = $this->saleRepository->changeStatus($sale['id'],'Anulada');

            if(!$response){
                throw new \Exception('Ha ocurrido un error anulando la factura de venta.', 500);
                DB::rollBack();
            }

            DB::commit();

            return ['status' => true, 'message' => 'Venta Anulada correctamente', 'code' => 200];

        } catch (\Throwable $exception) {
            return ['status' => false, 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    /**
     * @return int
     */
    private function getConsecutive(): int
    {
        return $this->saleRepository->getLastConsecutive() + 1;
    }

    /**
     * @param int $saleId
     * @param array $saleProducts
     * @return array
     */
    private function getSaleProductsWithRelationId(int $saleId, array $saleProducts): array
    {
        return array_map(function ($saleProduct) use ($saleId) {
            return [
                'sale_id' => $saleId,
                'product_id' => $saleProduct['product_id'],
                'name' => $saleProduct['name'],
                'description' => (!empty($saleProduct['description'])) ? $saleProduct['description'] : '',
                'warehouse_id' => $saleProduct['warehouse_id'],
                'quantity' => $saleProduct['quantity'],
                'price' => $saleProduct['price'],
                'discount_percentage' => $saleProduct['discount_percentage'],
                'vat' => $saleProduct['vat'],
            ];
        }, $saleProducts);
    }

    /**
     * @param int $saleId
     * @param array $salePayments
     * @return array
     */
    private function getSalePaymentsWithRelationId(int $saleId, array $salePayments): array
    {
        return array_map(function ($salePayment) use ($saleId) {
            return [
                'sale_id' => $saleId,
                'way_to_pay' => $salePayment['way_to_pay'],
                'amount' => $salePayment['amount'],
                'method' => $salePayment['method'],
                'date' => Carbon::now()->format('Y-m-d h:i:s'),
            ];
        }, $salePayments);
    }
}
