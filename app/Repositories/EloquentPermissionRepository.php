<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;

/**
 * Class EloquentPermissionRepository
 * @package App\Repositories
 */
class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    /**
     * @var Permission
     */
    protected $permission;

    /**
     * EloquentPermissionRepository constructor.
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $permissions = $this->permission->all();

        return (!empty($permissions)) ? $permissions->toArray() : [];
    }

    /**
     * Obtener permisos por un listado de ids
     *
     * @param array $ids
     * @return array
     */
    public function getByListIds(array $ids): array
    {
        $permissions = $this->permission->whereIn('id', $ids)->get('id');

        return (!empty($permissions)) ? $permissions->pluck('id')->toArray() : [];
    }

}
