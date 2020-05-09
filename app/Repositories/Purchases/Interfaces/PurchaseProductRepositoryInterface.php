<?php

namespace App\Repositories\Purchases\Interfaces;

/**
 * Interface PurchaseProductRepositoryInterface
 * @package App\Repositories\Purchases\Interfaces
 */
interface PurchaseProductRepositoryInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data): bool;
}
