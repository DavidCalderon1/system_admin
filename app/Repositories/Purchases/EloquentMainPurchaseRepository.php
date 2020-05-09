<?php

namespace App\Repositories\Purchases;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Purchases\Interfaces\MainPurchaseRepositoryInterface;
use App\Repositories\Purchases\Interfaces\PurchasePaymentRepositoryInterface;
use App\Repositories\Purchases\Interfaces\PurchaseProductRepositoryInterface;
use App\Repositories\Purchases\Interfaces\PurchaseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentMainPurchaseRepository
 * @package App\Repositories\Purchases
 */
class EloquentMainPurchaseRepository implements MainPurchaseRepositoryInterface
{
    /**
     * @var PurchaseRepositoryInterface
     */
    protected $purchaseRepository;

    /**
     * @var PurchaseProductRepositoryInterface
     */
    protected $purchaseProductRepository;

    /**
     * @var PurchasePaymentRepositoryInterface
     */
    protected $purchasePaymentRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * EloquentMainPurchaseRepository constructor.
     * @param PurchaseRepositoryInterface $purchaseRepository
     * @param PurchaseProductRepositoryInterface $purchaseProductRepository
     * @param PurchasePaymentRepositoryInterface $purchasePaymentRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        PurchaseRepositoryInterface $purchaseRepository,
        PurchaseProductRepositoryInterface $purchaseProductRepository,
        PurchasePaymentRepositoryInterface $purchasePaymentRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->purchaseProductRepository = $purchaseProductRepository;
        $this->purchasePaymentRepository = $purchasePaymentRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $purchaseData
     * @param array $purchaseProducts
     * @param array $purchasePayments
     * @return array
     * @throws \Exception
     */
    public function create(array $purchaseData, array $purchaseProducts, array $purchasePayments): array
    {
        try {
            DB::beginTransaction();
            dd($purchaseData, $purchaseProducts, $purchasePayments);
            $purchaseData['prefix'] = 'FC';
            $purchaseData['consecutive'] = $this->getConsecutive();
            $purchaseData['status'] = 'Activa';

            $purchaseSaved = $this->purchaseRepository->create($purchaseData);

            if (empty($purchaseSaved)) {
                throw new \Exception('Ha ocurrido un error almacenando la venta.', 500);
                DB::rollBack();
            }

            foreach ($purchaseProducts as $key => $purchaseProduct) {
                $purchaseProducts[$key]['purchase_id'] = $purchaseSaved->id;

                $this->productRepository->updatePivotSumQuantity(
                    $purchaseProduct['product_id'],
                    $purchaseProduct['warehouse_id'],
                    $purchaseProduct['quantity']
                );
            }

            $purchaseProducts = $this->getPurchaseProductsWithRelationId($purchaseSaved->id, $purchaseProducts);
            $purchasePayments = $this->getPurchasePaymentsWithRelationId($purchaseSaved->id, $purchasePayments, $purchaseData['date']);

            $purchaseProductSaved = $this->purchaseProductRepository->create($purchaseProducts);
            $purchasePaymentSaved = $this->purchasePaymentRepository->create($purchasePayments);

            if (empty($purchaseProductSaved) || empty($purchasePaymentSaved)) {
                throw new \Exception('Ha ocurrido un error almacenando la compra.', 500);
                DB::rollBack();
            }

            DB::commit();

            return ['status' => true, 'message' => 'Compra registrada correctamente', 'code' => 201, 'purchase' => $purchaseSaved];

        } catch (\Throwable $exception) {
            return ['status' => false, 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    /**
     * @param $purchaseId
     * @return array
     */
    public function cancel($purchaseId): array
    {
        try {
            DB::beginTransaction();

            $purchase = $this->purchaseRepository->get($purchaseId);

            foreach ($purchase['purchase_products'] as $key => $purchaseProduct) {
                $response = $this->productRepository->updatePivotSubtractQuantity(
                    $purchaseProduct['product_id'],
                    $purchaseProduct['warehouse_id'],
                    $purchaseProduct['quantity']
                );

                if (!$response) {
                    throw new \Exception('Ha ocurrido un error anulando la factura de compra.', 500);
                    DB::rollBack();
                }
            }
            $response = $this->purchaseRepository->changeStatus($purchase['id'], 'Anulada');

            if (!$response) {
                throw new \Exception('Ha ocurrido un error anulando la factura de compra.', 500);
                DB::rollBack();
            }

            DB::commit();

            return ['status' => true, 'message' => 'Compra Anulada correctamente', 'code' => 200];

        } catch (\Throwable $exception) {
            return ['status' => false, 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    /**
     * @return int
     */
    private function getConsecutive(): int
    {
        return $this->purchaseRepository->getLastConsecutive() + 1;
    }

    /**
     * @param int $purchaseId
     * @param array $purchaseProducts
     * @return array
     */
    private function getPurchaseProductsWithRelationId(int $purchaseId, array $purchaseProducts): array
    {
        return array_map(function ($purchaseProduct) use ($purchaseId) {
            return [
                'purchase_id' => $purchaseId,
                'product_id' => $purchaseProduct['product_id'],
                'name' => $purchaseProduct['name'],
                'description' => (!empty($purchaseProduct['description'])) ? $purchaseProduct['description'] : '',
                'warehouse_id' => $purchaseProduct['warehouse_id'],
                'quantity' => $purchaseProduct['quantity'],
                'price' => $purchaseProduct['price'],
                'discount_percentage' => $purchaseProduct['discount_percentage'],
                'vat' => $purchaseProduct['vat'],
            ];
        }, $purchaseProducts);
    }

    /**
     * @param int $purchaseId
     * @param array $purchasePayments
     * @param string $purchaseDate
     * @return array
     */
    private function getPurchasePaymentsWithRelationId(int $purchaseId, array $purchasePayments, string $purchaseDate): array
    {
        return array_map(function ($purchasePayment) use ($purchaseId, $purchaseDate) {

            $creditExpirationDate = null;

            if ($purchasePayment['way_to_pay'] === 'credit') {
                $creditExpirationDate = strtotime("+{$purchasePayment['days_to_pay']} day", strtotime($purchaseDate));
                $creditExpirationDate = date('Y-m-d', $creditExpirationDate);
            }

            return [
                'purchase_id' => $purchaseId,
                'way_to_pay' => $purchasePayment['way_to_pay'],
                'amount' => $purchasePayment['amount'],
                'method' => $purchasePayment['method'],
                'days_to_pay' => $purchasePayment['days_to_pay'],
                'credit_expiration_date' => $creditExpirationDate,
                'date' => Carbon::now()->format('Y-m-d h:i:s'),
            ];
        }, $purchasePayments);
    }
}
