<?php

namespace App\Repositories\Sales\Interfaces;

use App\Models\SaleProduct;

/**
 * Interface SaleProductRepositoryInterface
 * @package App\Repositories\Sales\Interfaces
 */
interface SaleProductRepositoryInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool;
}
