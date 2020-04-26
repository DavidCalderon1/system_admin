<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Constants\PermissionsConstants;

/**
 * Class UserAdminSeeder
 */
class UserSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('slug', PermissionsConstants::ROLE_ADMIN)->first();

        $manager = new User();
        $manager->name = 'Admin';
        $manager->email = 'admin@admin.com';
        $manager->password = bcrypt('admin');
        $manager->save();
        $manager->roles()->attach($adminRole);
    }
}
