<?php

namespace App\Repositories\Interfaces;

/**
 * Interface RoleRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface RoleRepositoryInterface
{
    /**
     * Obtener todos los roles
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Obtener roles por un listado de ids
     *
     * @param array $ids
     * @return array
     */
    public function getByListIds(array $ids): array;
}
