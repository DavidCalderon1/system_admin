<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface ProductRepositoryInterface
{

    /**
     * @param int $perPage
     * @param array $filers
     * @return mixed
     */
    public function getPagination(int $perPage, array $filers = []);

    /**
     * @param int $warehouseId
     * @return array
     */
    public function get(int $warehouseId): array;

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * @param int $categoryId
     * @return bool
     */
    public function existWithCategoryId(int $categoryId): bool;

}
