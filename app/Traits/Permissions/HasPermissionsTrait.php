<?php

namespace App\Traits\Permissions;

use App\Models\Permission;
use App\Models\Role;

trait HasPermissionsTrait
{
    /**
     * @param mixed ...$permissions
     * @return bool
     */
    public function givePermissionsTo(...$permissions)
    {
        try{
            $permissions = $this->getAllPermissions($permissions);

            if (empty($permissions->toArray())) {
                return false;
            }

            $this->permissions()->saveMany($permissions);

            return true;
        }catch (\Exception $exception){
            return false;
        }
    }

    /**
     * @param mixed ...$permissions
     * @return $this
     */
    public function withdrawPermissionsTo(...$permissions): self
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    /**
     * @param mixed ...$permissions
     * @return HasPermissionsTrait
     */
    public function refreshPermissions(...$permissions): HasPermissionsTrait
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionTo($permission): bool
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermissionThroughRole($permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param mixed ...$roles
     * @return bool
     */
    public function hasRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    /**
     * @param $permission
     * @return bool
     */
    protected function hasPermission($permission)
    {
        return (bool)$this->permissions->where('slug', $permission->slug)->count();
    }

    /**
     * @param array $permissions
     * @return mixed
     */
    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('slug', $permissions)->get();
    }

}
