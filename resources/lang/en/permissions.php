<?php

use App\Constants\PermissionsConstants;

return [
    'unauthorized' => 'You do not have permissions to perform this action.',
    'slug' => [
        PermissionsConstants::USER_LIST => 'List users',
        PermissionsConstants::USER_CREATE => 'Create users',
        PermissionsConstants::USER_UPDATE => 'Update users',
        PermissionsConstants::USER_DELETE => 'Delete users',
        PermissionsConstants::ROLE_LIST => 'List roles',
        PermissionsConstants::ROLE_CREATE => 'Create roles',
        PermissionsConstants::ROLE_UPDATE => 'Update roles',
        PermissionsConstants::ROLE_DELETE => 'Delete roles',
        PermissionsConstants::THIRD_LIST => 'List Third Parties',
        PermissionsConstants::THIRD_CREATE => 'Create Third Parties',
        PermissionsConstants::THIRD_UPDATE => 'Update Third Parties',
        PermissionsConstants::THIRD_DELETE => 'Delete Third Parties',
        PermissionsConstants::INVENTORY_CATEGORY_LIST => 'List Category Products',
        PermissionsConstants::INVENTORY_CATEGORY_CREATE => 'Create Category Products',
        PermissionsConstants::INVENTORY_CATEGORY_UPDATE => 'Update Category Products',
        PermissionsConstants::INVENTORY_CATEGORY_DELETE => 'Delete Category Products',
        PermissionsConstants::WAREHOUSE_LIST => 'List warehouses',
        PermissionsConstants::WAREHOUSE_CREATE => 'Create warehouses',
        PermissionsConstants::WAREHOUSE_UPDATE => 'Update warehouses',
        PermissionsConstants::WAREHOUSE_DELETE => 'Delete warehouses',
        PermissionsConstants::PRODUCT_LIST => 'List Products',
        PermissionsConstants::PRODUCT_CREATE => 'Create Products',
        PermissionsConstants::PRODUCT_UPDATE => 'Update Products',
        PermissionsConstants::PRODUCT_DELETE => 'Delete Products',
        PermissionsConstants::SALE_LIST => 'list Sales',
        PermissionsConstants::SALE_VIEW => 'View Sale Details',
        PermissionsConstants::SALE_CREATE => 'Create Sales',
        PermissionsConstants::SALE_CANCEL => 'Cancel Sales',
        PermissionsConstants::PURCHASE_LIST => 'list Purchases',
        PermissionsConstants::PURCHASE_VIEW => 'View Purchase Details',
        PermissionsConstants::PURCHASE_CREATE => 'Create Purchases',
        PermissionsConstants::PURCHASE_CANCEL => 'Cancel Purchases',
    ]
];
