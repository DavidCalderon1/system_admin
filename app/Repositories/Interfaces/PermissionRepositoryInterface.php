<?php

namespace App\Repositories\Interfaces;

/**
 * Interface PermissionRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface PermissionRepositoryInterface
{
    /**
     * Obtener todos los permisos
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Obtener permisos por un listado de ids
     *
     * @param array $ids
     * @return array
     */
    public function getByListIds(array $ids): array;
}
