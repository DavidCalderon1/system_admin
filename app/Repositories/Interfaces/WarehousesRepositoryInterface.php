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
    public function getPagination(int $perPage, array $filters = []): array;

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
