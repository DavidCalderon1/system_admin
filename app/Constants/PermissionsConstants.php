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
}