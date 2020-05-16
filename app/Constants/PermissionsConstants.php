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
     * Permisos crud modulo de usuarios en la configuracion
     */
    public const USER_LIST = 'user-list';
    public const USER_CREATE = 'user-create';
    public const USER_UPDATE = 'user-update';
    public const USER_DELETE = 'user-delete';

    /**
     * Permisos crud modulo de roles en la configuracion
     */
    public const ROLE_LIST = 'role-list';
    public const ROLE_CREATE = 'role-create';
    public const ROLE_UPDATE = 'role-update';
    public const ROLE_DELETE = 'role-delete';

    /**
     * Permisos crud modulo de taxes en la configuracion
     */
    public const CONFIG_TAXES_LIST = 'config-taxes-list';
    public const CONFIG_TAXES_CREATE = 'config-taxes-create';
    public const CONFIG_TAXES_UPDATE = 'config-taxes-update';
    public const CONFIG_TAXES_DELETE = 'config-taxes-delete';

    /**
     * Permisos crud modulo de Terceros
     */
    public const THIRD_LIST = 'third-list';
    public const THIRD_VIEW = 'third-view';
    public const THIRD_CREATE = 'third-create';
    public const THIRD_UPDATE = 'third-update';
    public const THIRD_DELETE = 'third-delete';


    /**
     * Permisos crud modulo de Categorias de productos
     */
    public const INVENTORY_CATEGORY_LIST = 'inventory-category-list';
    public const INVENTORY_CATEGORY_CREATE = 'inventory-category-create';
    public const INVENTORY_CATEGORY_UPDATE = 'inventory-category-update';
    public const INVENTORY_CATEGORY_DELETE = 'inventory-category-delete';

    /**
     * Permisos crud modulo de bodegas
     */
    public const WAREHOUSE_LIST = 'warehouse-list';
    public const WAREHOUSE_CREATE = 'warehouse-create';
    public const WAREHOUSE_UPDATE = 'warehouse-update';
    public const WAREHOUSE_DELETE = 'warehouse-delete';

    /**
     * Permisos crud modulo de productos
     */
    public const PRODUCT_LIST = 'products-list';
    public const PRODUCT_VIEW = 'products-view';
    public const PRODUCT_CREATE = 'products-create';
    public const PRODUCT_UPDATE = 'products-update';
    public const PRODUCT_DELETE = 'products-delete';

    /**
     * Permisos crud modulo de ventas
     */
    public const SALE_LIST = 'sale-list';
    public const SALE_VIEW = 'sale-view';
    public const SALE_EDIT = 'sale-edit';
    public const SALE_CREATE = 'sale-create';
    public const SALE_CANCEL = 'sale-cancel';

    /**
     * Permisos crud modulo de compras
     */
    public const PURCHASE_LIST = 'purchase-list';
    public const PURCHASE_VIEW = 'purchase-view';
    public const PURCHASE_EDIT = 'purchase-edit';
    public const PURCHASE_CREATE = 'purchase-create';
    public const PURCHASE_CANCEL = 'purchase-cancel';
}
