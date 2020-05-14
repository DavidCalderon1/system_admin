<?php

namespace App\UsesCases;

use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface;
use App\Repositories\Sales\Interfaces\SaleRepositoryInterface;
use App\UsesCases\Interfaces\SalesUseCaseInterface;

/**
 * Class SalesUseCase
 * @package App\UsesCases
 */
class SalesUseCase implements SalesUseCaseInterface
{
    /**
     * @var SaleRepositoryInterface
     */
    protected $saleRepository;

    /**
     * @var ProductWarehouseRepositoryInterface
     */
    protected $productWarehouseRepository;

    /**
     * SalesUseCase constructor.
     * @param SaleRepositoryInterface $saleRepository
     * @param ProductWarehouseRepositoryInterface $productWarehouseRepository
     */
    public function __construct(
        SaleRepositoryInterface $saleRepository,
        ProductWarehouseRepositoryInterface $productWarehouseRepository
    )
    {
        $this->saleRepository = $saleRepository;
        $this->productWarehouseRepository = $productWarehouseRepository;
    }

    /**
     * @param int $saleId
     * @return array
     */
    public function getById(int $saleId): array
    {
        $sale = $this->saleRepository->get($saleId);
        $sale['sale_products'] = $this->getProductsWhitTotal($sale['sale_products']);
        $sale['sale_payments'] = $this->getPaymentsTrans($sale['sale_payments']);
        $sale['totals'] = $this->getTotalValues($sale['sale_products']);
        $sale['totals']['total_payment'] = numberFormat(getTotalPayments($sale['sale_payments']));

        return $sale;
    }

    /**
     * @param int $saleId
     * @return array
     */
    public function getByIdForEdit(int $saleId): array
    {
        $sale = $this->saleRepository->get($saleId);

        $sale['text'] = $sale['client_identity_number'] . ' - ' . $sale['client_name'];
        $ext = (!empty($sale['client']['phone_extension'])) ? ' Ext: ' . $sale['client']['phone_extension'] : '';
        $sale['options_client_contact'] = [
            $sale['client']['phone_number'] . $ext,
            $sale['client']['email']
        ];

        foreach ($sale['sale_products'] as $key => $saleProduct) {
            $sale['sale_products'][$key]['code'] = $saleProduct['product']['code'];
            $sale['sale_products'][$key]['reference'] = $saleProduct['product']['reference'];
            $sale['sale_products'][$key]['text'] = $saleProduct['product']['code'] . ' - ' . $saleProduct['product']['reference'];
            $sale['sale_products'][$key]['warehouses'] = $saleProduct['product']['warehouses'];
        }

        $sale['sale_products'] = $this->productWarehouseRepository->getProductForSelect($sale['sale_products']);

        return $sale;
    }

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return array
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue): array
    {
        $sales = $this->saleRepository->getPagination($length, $orderBy, $orderByDir, $searchValue);

        foreach ($sales['data'] as $key => $item) {
            $sales['data'] [$key]['totals'] = $this->getTotalValues($item['sale_products']);
        }

        return $sales;
    }

    /**
     * @param array $saleProducts
     * @return array[]
     */
    private function getProductsWhitTotal(array $saleProducts)
    {
        return array_map(function ($saleProduct) {
            $total = $this->getProductTotal(
                $saleProduct['price'],
                $saleProduct['vat'],
                $saleProduct['discount_percentage'],
                $saleProduct['quantity']
            );

            return [
                'id' => $saleProduct['id'],
                'sale_id' => $saleProduct['sale_id'],
                'product_id' => $saleProduct['product_id'],
                'warehouse_id' => $saleProduct['warehouse_id'],
                'name' => $saleProduct['name'],
                'price' => $saleProduct['price'],
                'price_formatted' => numberFormat($saleProduct['price']),
                'quantity' => $saleProduct['quantity'],
                'discount_percentage' => $saleProduct['discount_percentage'],
                'vat' => $saleProduct['vat'],
                'description' => $saleProduct['description'],
                'total' => $total,
                'total_formatted' => numberFormat($total),
            ];
        }, $saleProducts);
    }

    /**
     * @param float $price
     * @param float $vat
     * @param float $discountPercentage
     * @param int $quantity
     * @return float
     */
    private function getProductTotal(float $price, float $vat, float $discountPercentage, int $quantity): float
    {
        $vat = $price * $vat / 100;

        $discount = $price * $discountPercentage / 100;

        $total = ($price - $discount + $vat) * $quantity;

        return round($total, 2);
    }

    /**
     * @param array $saleProducts
     * @return array
     */
    private function getTotalValues(array $saleProducts): array
    {
        $totalGross = 0;
        $totalDiscount = 0;
        $totalVat = 0;

        array_walk($saleProducts, function ($saleProduct)
        use (&$totalGross, &$totalDiscount, &$totalVat) {
            $totalGross += $this->getProductGross($saleProduct['price'], $saleProduct['quantity']);
            $totalDiscount += $this->getProductDiscount($saleProduct['price'], $saleProduct['discount_percentage'], $saleProduct['quantity']);
            $totalVat += $this->getProductVat($saleProduct['price'], $saleProduct['vat'], $saleProduct['quantity']);
        });

        $subTotal = round($totalGross - $totalDiscount, 2);
        $total = round($subTotal + $totalVat, 2);

        $totalGross = round($totalGross, 2);
        $totalDiscount = round($totalDiscount, 2);
        $totalVat = round($totalVat, 2);

        return [
            'total_gross' => $totalGross,
            'total_gross_formatted' => numberFormat($totalGross),
            'total_discount' => $totalDiscount,
            'total_discount_formatted' => numberFormat($totalDiscount),
            'total_vat' => $totalVat,
            'total_vat_formatted' => numberFormat($totalVat),
            'sub_total' => $subTotal,
            'sub_total_formatted' => numberFormat($subTotal),
            'total' => $total,
            'total_formatted' => numberFormat($total),
        ];
    }

    /**
     * @param float $price
     * @param int $quantity
     * @return float
     */
    private function getProductGross(float $price, int $quantity): float
    {
        return round($price * $quantity, 2);
    }

    /**
     * @param float $price
     * @param float $discountPercentage
     * @param int $quantity
     * @return float
     */
    private function getProductDiscount(float $price, float $discountPercentage, int $quantity): float
    {
        $discount = $price * $discountPercentage / 100;

        return round($discount * $quantity, 2);
    }

    /**
     * @param float $price
     * @param float $vat
     * @param int $quantity
     * @return float
     */
    private function getProductVat(float $price, float $vat, int $quantity): float
    {
        $unitVat = $price * $vat / 100;

        return round($unitVat * $quantity, 2);
    }

    /**
     * @param array $salePayments
     * @return array
     */
    private function getPaymentsTrans(array $salePayments): array
    {
        return getPaymentsTrans($salePayments);
    }
}
