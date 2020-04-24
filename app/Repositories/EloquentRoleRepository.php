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
     * @param $roleId
     * @return Role
     */
    public function get($roleId): Role
    {
        return $this->role->with('permissions')->where('id', $roleId)->first();
    }

    /**
     * @param int $perPage
     * @param array $filter
     * @return mixed
     */
    public function getPagination(int $perPage, array $filter = []): array
    {
        $roles = $this->role->select('id', 'name', 'slug');

        if (!empty($filter['name'])) {
            $roles->where('name', 'like', "%{$filter['name']}%");
        } elseif (!empty($filter['slug'])) {
            $roles->where('slug', 'like', "%{$filter['slug']}%");
        }

        $roles = $roles->orderBy('id', 'asc')->paginate($perPage)->toArray();

        return (!empty($roles['data'])) ? $roles : [];
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

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return $this->role->create([
            'name' => ucwords(strtolower($data['name'])),
            'slug' => strtolower($data['slug']),
        ]);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $roleData = [
            'name' => ucwords(strtolower($data['name'])),
            'slug' => strtolower($data['slug']),
        ];

        return $this->role->where('id', $id)->update($roleData);
    }

    public function destroy(int $id)
    {
        return $this->role->where('id', $id)->delete();
    }

    /**
     * @param Role $role
     * @param array $permissionsIds
     */
    public function addPermissions(Role $role, array $permissionsIds): void
    {
        $role->permissions()->attach($permissionsIds);
    }

    /**
     * @param Role $role
     * @param array $permissionsIds
     * @return array
     */
    public function updatePermissions(Role $role, array $permissionsIds): array
    {
        return $role->permissions()->sync($permissionsIds);
    }

    /**
     * @param Role $role
     * @return array
     */
    public function cleanPermissions(Role $role): array
    {
        return $this->updatePermissions($role, []);
    }
}
