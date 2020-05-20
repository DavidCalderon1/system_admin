<?php

namespace App\Repositories\Interfaces;

/**
 * Interface CostCenterRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface CostCenterRepositoryInterface
{
    /**
     * @return array
     */
    public function all(): array;

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
     * @param array $data
     * @return array
     */
    public function create(array $data): array;

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
