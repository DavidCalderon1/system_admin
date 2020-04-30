<?php

use App\Constants\PermissionsConstants;

return [
    'unauthorized' => 'No tienes permisos para realizar esta acción.',
    'slug' => [
        PermissionsConstants::USER_LIST => 'Listar usuarios',
        PermissionsConstants::USER_CREATE => 'Crear usuarios',
        PermissionsConstants::USER_UPDATE => 'Actualizar usuarios',
        PermissionsConstants::USER_DELETE => 'Eliminar usuarios',
        PermissionsConstants::ROLE_LIST => 'Listar roles',
        PermissionsConstants::ROLE_CREATE => 'Crear roles',
        PermissionsConstants::ROLE_UPDATE => 'Actualizar roles',
        PermissionsConstants::ROLE_DELETE => 'Eliminar roles',
        PermissionsConstants::THIRD_LIST => 'Listar Terceros',
        PermissionsConstants::THIRD_CREATE => 'Crear Terceros',
        PermissionsConstants::THIRD_UPDATE => 'Actualizar Terceros',
        PermissionsConstants::THIRD_DELETE => 'Eliminar Terceros',
        PermissionsConstants::INVENTORY_CATEGORY_LIST => 'Listar Categorias de Productos',
        PermissionsConstants::INVENTORY_CATEGORY_CREATE => 'Crear Categorias de Productos',
        PermissionsConstants::INVENTORY_CATEGORY_UPDATE => 'Actualizar Categorias de Productos',
        PermissionsConstants::INVENTORY_CATEGORY_DELETE => 'Eliminar Categorias de Productos',
        PermissionsConstants::WAREHOUSE_LIST => 'Listar Bodegas',
        PermissionsConstants::WAREHOUSE_CREATE => 'Crear Bodegas',
        PermissionsConstants::WAREHOUSE_UPDATE => 'Actualizar Bodegas',
        PermissionsConstants::WAREHOUSE_DELETE => 'Eliminar Bodegas',
        PermissionsConstants::PRODUCT_LIST => 'Listar Productos',
        PermissionsConstants::PRODUCT_CREATE => 'Crear Productos',
        PermissionsConstants::PRODUCT_UPDATE => 'Actualizar Productos',
        PermissionsConstants::PRODUCT_DELETE => 'Eliminar Productos',
    ]
];
