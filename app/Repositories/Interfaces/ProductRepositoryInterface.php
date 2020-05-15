<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface ProductRepositoryInterface
{

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValues
     * @return array
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValues): array;

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param string $filter
     * @return array
     */
    public function filter(string $filter): array;

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
