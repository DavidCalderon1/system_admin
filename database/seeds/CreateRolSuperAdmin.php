<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

/**
 * Class CreateRolSuperAdmin
 */
class CreateRolSuperAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->slug = 'super-admin';
        $role->name = 'Super Admin';
        $role->save();
    }
}
