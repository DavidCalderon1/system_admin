<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

/**
 * Class EloquentRoleRepository
 * @package App\Repositories
 */
class EloquentRoleRepository implements RoleRepositoryInterface
{
    /**
     * @var Role
     */
    protected $role;

    /**
     * EloquentRoleRepository constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $roles = $this->role->all();

        return (!empty($roles)) ? $roles->toArray() : [];
    }

    /**
     * Obtener roles por un listado de ids
     * @param array $ids
     * @return array
     */
    public function getByListIds(array $ids): array
    {
        $roles = $this->role->whereIn('id', $ids)->get('id');

        return (!empty($roles)) ? $roles->pluck('id')->toArray() : [];
    }

}
