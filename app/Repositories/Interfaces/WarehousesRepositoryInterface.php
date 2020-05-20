<?php

namespace App\Repositories\Interfaces;

/**
 * Interface WarehousesRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface WarehousesRepositoryInterface
{
    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValues
     * @return array
     */
    public function getPagination(
        int $length,
        string $orderBy,
        string $orderByDir,
        string $searchValues
    ): array;

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
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data);

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
