<?php

namespace App\Repositories\Purchases;

use App\Models\PurchaseProduct;
use App\Repositories\Purchases\Interfaces\PurchaseProductRepositoryInterface;

/**
 * Class EloquentPurchaseProductRepository
 * @package App\Repositories\Purchases
 */
class EloquentPurchaseProductRepository implements PurchaseProductRepositoryInterface
{
    /**
     * @var PurchaseProduct
     */
    protected $purchaseProduct;

    /**
     * EloquentPurchaseProductRepository constructor.
     * @param PurchaseProduct $purchaseProduct
     */
    public function __construct(PurchaseProduct $purchaseProduct)
    {
        $this->purchaseProduct = $purchaseProduct;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        return $this->purchaseProduct->insert($data);
    }

    /**
     * @param $purchaseId
     * @return bool
     */
    public function deleteProductsByPurchaseId($purchaseId): bool
    {
        return $this->purchaseProduct->where('purchase_id', $purchaseId)->delete();
    }
}
