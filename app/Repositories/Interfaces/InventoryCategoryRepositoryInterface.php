<?php

namespace App\Repositories\Interfaces;

/**
 * Interface InventoryCategoryInterface
 * @package App\Repositories\Interfaces
 */
interface InventoryCategoryRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAll(): array;

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
     * @param int $id
     * @return array
     */
    public function get(int $id): array;

    /**
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function update(int $id, $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
