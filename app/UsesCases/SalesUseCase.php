<?php

namespace App\UsesCases;

use App\Repositories\Sales\Interfaces\SaleRepositoryInterface;
use App\UsesCases\Interfaces\SalesUseCaseInterface;
use Carbon\Carbon;

/**
 * Class SalesUseCase
 * @package App\UsesCases
 */
class SalesUseCase implements SalesUseCaseInterface
{
    /**
     * @var
     */
    protected $saleRepository;

    /**
     * SaleExportPdfController constructor.
     * @param SaleRepositoryInterface $saleRepository
     */
    public function __construct(SaleRepositoryInterface $saleRepository)
    {
        $this->saleRepository = $saleRepository;
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
        $sale['totals']['total_payment'] =  numberFormat(getTotalPayments($sale['sale_payments']));

        return $sale;
    }

    /**
     * @param int $perPages
     * @param array $filters
     * @return array
     */
    public function getPagination(int $perPages, array $filters = []): array
    {
        $sales = $this->saleRepository->getPagination($perPages, $filters);
        foreach ($sales['data'] as $key => $datum) {
            $sales['data'][$key]['sale_products'] = $this->getProductsWhitTotal($datum['sale_products']);
            $sales['data'][$key]['totals'] = $this->getTotalValues($datum['sale_products']);
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
            $total =  $this->getProductTotal(
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
                'price_formatted' =>  numberFormat($saleProduct['price']),
                'quantity' => $saleProduct['quantity'],
                'discount_percentage' => $saleProduct['discount_percentage'],
                'vat' => $saleProduct['vat'],
                'description' => $saleProduct['description'],
                'total' =>$total,
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
