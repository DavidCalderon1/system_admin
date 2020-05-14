<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductWarehouseRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface ProductWarehouseRepositoryInterface
{
    /**
     * @param $filter
     * @return array
     */
    public function filterForSelect($filter): array;
}
