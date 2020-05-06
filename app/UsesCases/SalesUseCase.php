<?php

namespace App\UsesCases;

use App\Repositories\Sales\Interfaces\SaleRepositoryInterface;
use App\UsesCases\Interfaces\SalesUseCaseInterface;

/**
 * Class InvoicesUseCase
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
        $sale['totals']['total_payment'] = $this->getTotalPayments($sale['sale_payments']);

        return $sale;
    }

    /**
     * @param array $saleProducts
     * @return array[]
     */
    private function getProductsWhitTotal(array $saleProducts)
    {
        return array_map(function ($saleProduct) {
            return [
                'id' => $saleProduct['id'],
                'sale_id' => $saleProduct['sale_id'],
                'product_id' => $saleProduct['product_id'],
                'warehouse_id' => $saleProduct['warehouse_id'],
                'name' => $saleProduct['name'],
                'price' => $saleProduct['price'],
                'quantity' => $saleProduct['quantity'],
                'discount_percentage' => $saleProduct['discount_percentage'],
                'vat' => $saleProduct['vat'],
                'description' => $saleProduct['description'],
                'total' => $this->getProductTotal(
                    $saleProduct['price'],
                    $saleProduct['vat'],
                    $saleProduct['discount_percentage'],
                    $saleProduct['quantity']
                ),
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

        return [
            'total_gross' => round($totalGross, 2),
            'total_discount' => round($totalDiscount, 2),
            'total_vat' => round($totalVat, 2),
            'sub_total' => $subTotal,
            'total' => $total,
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

    private function getPaymentsTrans(array $salePayments)
    {
        return array_map(function ($salePayment) {
            return [
                'way_to_pay' => __('sales.payment_' . $salePayment['way_to_pay']),
                'amount' => $salePayment['amount'],
                'method' => $salePayment['method'],
                'date' => $salePayment['date'],
            ];
        }, $salePayments);
    }

    /**
     * @param array $salePayments
     * @return false|float
     */
    private function getTotalPayments(array $salePayments)
    {
        $totalPayments = 0;

        array_walk($salePayments, function ($salePayment) use (&$totalPayments) {
            $totalPayments += $salePayment['amount'];
        });

        return round($totalPayments, 2);
    }
}
