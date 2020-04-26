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
    ]
];
