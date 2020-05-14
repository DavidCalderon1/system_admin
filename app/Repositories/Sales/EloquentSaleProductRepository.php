<?php

namespace App\Repositories\Sales;

use App\Models\SaleProduct;
use App\Repositories\Sales\Interfaces\SaleProductRepositoryInterface;

/**
 * Class EloquentSaleProductRepository
 * @package App\Repositories
 */
class EloquentSaleProductRepository implements SaleProductRepositoryInterface
{
    /**
     * @var SaleProduct
     */
    protected $saleProduct;

    /**
     * EloquentSaleProductRepository constructor.
     * @param SaleProduct $saleProduct
     */
    public function __construct(SaleProduct $saleProduct)
    {
        $this->saleProduct = $saleProduct;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool
    {
        return $this->saleProduct->insert($data);
    }

    /**
     * @param int $saleId
     * @return bool
     */
    public function deleteProductsBySaleId(int $saleId): bool
    {
        return $this->saleProduct->where('sale_id', $saleId)->delete();
    }
}
