<?php

namespace App\Repositories\Interfaces;

/**
 * Interface UserRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface UserRepositoryInterface
{
    /**
     * @param int $perPage
     * @return array
     */
    public function getPagination(int $perPage): array;

    /**
     * @param $data
     * @return mixed
     */
    public function store($data);

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id);
}
