<?php

namespace App\Repositories\Interfaces;

use App\Models\Role;

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
     * @param $roleId
     * @return Role
     */
    public function get($roleId): Role;

    /**
     * Obtener roles por un listado de ids
     *
     * @param array $ids
     * @return array
     */
    public function getByListIds(array $ids): array;

    /**
     * @param Role $role
     * @param array $permissionsIds
     */
    public function addPermissions(Role $role, array $permissionsIds): void;

    /**
     * @param Role $role
     * @param array $permissionsIds
     * @return mixed
     */
    public function updatePermissions(Role $role, array $permissionsIds);

    /**
     * @param Role $role
     * @return array
     */
    public function cleanPermissions(Role $role): array;

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
