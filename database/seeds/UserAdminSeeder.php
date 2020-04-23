<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class UserAdminSeeder
 */
class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('slug', 'super-admin')->first();

        $manager = new User();
        $manager->name = 'Super Admin';
        $manager->email = 'super@admin.com';
        $manager->password = bcrypt('superadmin');
        $manager->save();
        $manager->roles()->attach($adminRole);
    }
}
