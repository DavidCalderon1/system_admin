<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Role
 * @package App\Models
 */
class Role extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_roles');
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $permissionSlug = (is_array($permission)) ? $permission['slug'] : $permission->slug;

        return (bool)$this->permissions->where('slug', $permissionSlug)->count();
    }
}
