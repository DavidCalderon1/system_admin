<?php

namespace App\UsesCases;

use App\Repositories\Purchases\Interfaces\PurchaseRepositoryInterface;
use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
use Carbon\Carbon;

/**
 * Class PurchasesUseCase
 * @package App\UsesCases
 */
class PurchasesUseCase implements PurchasesUseCaseInterface
{

    protected $purchaseRepository;

    /**
     * PurchasesUseCase constructor.
     * @param PurchaseRepositoryInterface $purchaseRepository
     */
    public function __construct(PurchaseRepositoryInterface $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * @param int $purchaseId
     * @return array
     */
    public function getById(int $purchaseId): array
    {
        $purchase = $this->purchaseRepository->get($purchaseId);
        $purchase['purchase_products'] = $this->getProductsTotalFormatted($purchase['purchase_products']);
        $purchase['purchase_payments'] = getPaymentsTrans($purchase['purchase_payments']);
        $purchase['totals'] = $this->getTotalValues($purchase['purchase_products']);
        $purchase['totals']['total_payment'] = numberFormat(getTotalPayments($purchase['purchase_payments']));

        return $purchase;
    }

    /**
     * @param int $perPages
     * @param array $filters
     * @return array
     */
    public function getPagination(int $perPages, array $filters = []): array
    {
        $sales = $this->purchaseRepository->getPagination($perPages, $filters);
        foreach ($sales['data'] as $key => $datum) {
            $sales['data'][$key]['sale_products'] = $this->getProductsWhitTotal($datum['sale_products']);
            $sales['data'][$key]['totals'] = $this->getTotalValues($datum['sale_products']);
        }

        return $sales;
    }

    private function getProductsTotalFormatted(array $products): array {
        return array_map(function ($product){
            return [
                'id' => $product['id'],
                'purchase_id' => $product['purchase_id'],
                'product_id' => $product['product_id'],
                'warehouse_id' => $product['warehouse_id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'quantity' => $product['quantity'],
                'vat' => $product['vat'],
                'withholding_tax_percentage' => $product['withholding_tax_percentage'],
                'total' => $product['total'],
                'total_formatted' => numberFormat($product['total']),
            ];
        }, $products);
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
     * @param array $purchaseProducts
     * @return array
     */
    private function getTotalValues(array $purchaseProducts): array
    {
        $totalGross = 0;
        $subTotal=0;
        $totalTaxes = 0;
        $totalVat = 0;

        array_walk($purchaseProducts, function ($purchaseProduct)
        use (&$totalGross, &$subTotal, &$totalTaxes, &$totalVat ) {
            $totalGross += $this->getProductGross($purchaseProduct['total'], $purchaseProduct['vat']);
            $subTotal += $this->getProductSubtotal($purchaseProduct['total'], $purchaseProduct['vat']);
            $totalTaxes += $this->getProductTax($purchaseProduct['total'], $purchaseProduct['withholding_tax_percentage']);
            $totalVat += $this->getProductVat($purchaseProduct['total'], $purchaseProduct['vat']);
        });

        $total = round($subTotal + $totalVat,2);

        return [
            'total_gross' => $totalGross,
            'total_gross_formatted' => numberFormat($totalGross),
            'sub_total' => $subTotal,
            'sub_total_formatted' => numberFormat($subTotal),
            'total_taxes' => $totalTaxes,
            'total_taxes_formatted' => numberFormat($totalTaxes),
            'total_vat' => $totalVat,
            'total_vat_formatted' => numberFormat($totalVat),
            'total' => $total,
            'total_formatted' => numberFormat($total),
        ];
    }

    /**
     * @param float $total
     * @param float $vat
     * @return float
     */
    private function getProductGross(float $total, float $vat): float
    {
        $vat = $this->getProductVat($total, $vat);

        return round($total - $vat, 2);
    }

    /**
     * @param float $total
     * @param float $vat
     * @return float
     */
    private function getProductSubtotal(float $total, float $vat): float
    {
        $subTotal = $total- $this->getProductVat($total, $vat);

        return round($subTotal, 2);
    }

    /**
     * @param float $total
     * @param float $vat
     * @return float
     */
    private function getProductVat(float $total, float $vat): float
    {
        $unitVat = $total * $vat / 100;

        return round($unitVat, 2);
    }

    /**
     * @param float $total
     * @param float $withholdingTaxPercentage
     * @return float
     */
    private function getProductTax(float $total, float $withholdingTaxPercentage): float
    {
        $tax = $total * $withholdingTaxPercentage / 100;

        return round($tax, 2);
    }
}
