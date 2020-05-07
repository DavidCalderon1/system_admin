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
     * @return array
     */
    public function getAll(): array;

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

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool;

    /**
     * @param int $productId
     * @param int $warehouseId
     * @param int $quantityToDiscount
     * @return bool
     */
    public function updatePivotSubtractQuantity(int $productId, int $warehouseId, int $quantityToDiscount): bool;

    /**
     * @param int $productId
     * @param int $warehouseId
     * @param int $quantityToSum
     * @return bool
     */
    public function updatePivotSumQuantity(int $productId, int $warehouseId, int $quantityToSum): bool;
}
