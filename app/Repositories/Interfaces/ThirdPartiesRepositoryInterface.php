<?php

namespace App\Repositories\Interfaces;

use App\Models\ThirdParties;

/**
 * Interface ThirdPartiesRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface ThirdPartiesRepositoryInterface
{
    /**
     * @param int $thirdId
     * @return array
     */
    public function get(int $thirdId): array;

    /**
     * @param int $id
     * @param array $request
     * @return mixed
     */
    public function update(int $id, array $request): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deactivate(int $id): bool;

    /**
     * @param int $perPage
     * @param array $filer
     * @return array
     */
    public function getPagination(int $perPage, array $filer = []): array;

    /**
     * @param $data
     * @return ThirdParties
     */
    public function store($data): ThirdParties;

    /**
     * @param string $filter
     * @return array
     */
    public function filterClientByIdentityNumber(string $filter): array;
}