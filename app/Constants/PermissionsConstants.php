<?php


namespace App\Constants;

/**
 * Class PermissionsConstants
 * @package App\Constants
 */
class PermissionsConstants
{
    /**
     * Rol de superadmin
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_ADMIN_ID = 1;

    /**
     * Permisos crud modulo de usuarios
     */
    public const USER_LIST = 'user-list';
    public const USER_CREATE = 'user-create';
    public const USER_UPDATE = 'user-update';
    public const USER_DELETE = 'user-delete';

    /**
     * Permisos crud modulo de roles
     */
    public const ROLE_LIST = 'role-list';
    public const ROLE_CREATE = 'role-create';
    public const ROLE_UPDATE = 'role-update';
    public const ROLE_DELETE = 'role-delete';

    /**
     * Permisos crud modulo de Terceros
     */
    public const THIRD_LIST = 'third-list';
    public const THIRD_CREATE = 'third-create';
    public const THIRD_UPDATE = 'third-update';
    public const THIRD_DELETE = 'third-delete';
}
