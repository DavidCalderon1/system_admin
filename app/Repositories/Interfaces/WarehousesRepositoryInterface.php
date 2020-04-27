<?php

namespace App\Repositories\Interfaces;

/**
 * Interface WarehousesRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface WarehousesRepositoryInterface
{
    /**
     * @param int $perPage
     * @param array $filters
     * @return array
     */
    public function getPagination(int $perPage, array $filters=[]): array;
}
