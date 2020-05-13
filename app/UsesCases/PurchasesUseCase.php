<?php

namespace App\UsesCases;

use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface;
use App\Repositories\Purchases\Interfaces\PurchaseRepositoryInterface;
use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class PurchasesUseCase
 * @package App\UsesCases
 */
class PurchasesUseCase implements PurchasesUseCaseInterface
{

    /**
     * @var PurchaseRepositoryInterface
     */
    protected $purchaseRepository;

    /**
     * @var ProductWarehouseRepositoryInterface
     */
    protected $productWarehouseRepository;

    /**
     * PurchasesUseCase constructor.
     * @param PurchaseRepositoryInterface $purchaseRepository
     * @param ProductWarehouseRepositoryInterface $productWarehouseRepository
     */
    public function __construct(
        PurchaseRepositoryInterface $purchaseRepository,
        ProductWarehouseRepositoryInterface $productWarehouseRepository
    )
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->productWarehouseRepository = $productWarehouseRepository;
    }

    /**
     * @param $purchaseId
     * @return array
     */
    public function getByIdForEdit($purchaseId): array
    {
        $purchase = $this->purchaseRepository->get($purchaseId);
        $purchase['text'] = $purchase['provider_identity_number'] . ' - ' . $purchase['provider_name'];

        foreach ($purchase['purchase_products'] as $key => $purchaseProduct) {
            $purchase['purchase_products'][$key]['code'] = $purchaseProduct['product']['code'];
            $purchase['purchase_products'][$key]['reference'] = $purchaseProduct['product']['reference'];
            $purchase['purchase_products'][$key]['text'] = $purchaseProduct['product']['code'] . ' - ' . $purchaseProduct['product']['reference'];
            $purchase['purchase_products'][$key]['warehouses'] = $purchaseProduct['product']['warehouses'];
        }

        $purchase['purchase_products'] = $this->productWarehouseRepository->getProductForSelect($purchase['purchase_products']);

        return $purchase;
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
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return LengthAwarePaginator
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue): LengthAwarePaginator
    {
        $purchases = $this->purchaseRepository->getPagination($length, $orderBy, $orderByDir, $searchValue);

        foreach ($purchases->items() as $key => $item) {
            $purchases->items()[$key]['totals'] = $this->getTotalValues($item->purchaseProducts->toArray());
        }

        return $purchases;
    }

    /**
     * @param array $products
     * @return array
     */
    private function getProductsTotalFormatted(array $products): array
    {
        return array_map(function ($product) {
            return [
                'id' => $product['id'],
                'purchase_id' => $product['purchase_id'],
                'product_id' => $product['product_id'],
                'warehouse_id' => $product['warehouse_id'],
                'name' => $product['name'],
                'text' => $product['name'],
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
     * @param array $purchaseProducts
     * @return array
     */
    public function getTotalValues(array $purchaseProducts): array
    {
        $totalGross = 0;
        $subTotal = 0;
        $totalTaxes = 0;
        $totalVat = 0;

        array_walk($purchaseProducts, function ($purchaseProduct)
        use (&$totalGross, &$subTotal, &$totalTaxes, &$totalVat) {
            $totalGross += $this->getProductGross($purchaseProduct['total'], $purchaseProduct['vat']);
            $subTotal += $this->getProductSubtotal($purchaseProduct['total'], $purchaseProduct['vat']);
            $totalTaxes += $this->getProductTax($purchaseProduct['total'], $purchaseProduct['withholding_tax_percentage']);
            $totalVat += $this->getProductVat($purchaseProduct['total'], $purchaseProduct['vat']);
        });

        $total = round($subTotal + $totalVat, 2);

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
        $subTotal = $total - $this->getProductVat($total, $vat);

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
