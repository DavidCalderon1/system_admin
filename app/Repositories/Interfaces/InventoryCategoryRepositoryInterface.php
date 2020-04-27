<?php

namespace App\Repositories\Interfaces;

/**
 * Interface InventoryCategoryInterface
 * @package App\Repositories\Interfaces
 */
interface InventoryCategoryRepositoryInterface
{
    /**
     * @param int $perPage
     * @param array $filer
     * @return array
     */
    public function getPagination(int $perPage, array $filer = []): array;

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
