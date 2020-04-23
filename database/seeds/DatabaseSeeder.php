<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //El orden de ejecucion es importante
        $this->call(CreateRolSuperAdmin::class);
        $this->call(CreateUserSuperAdminSeeder::class);
        $this->call(CreatePermissionsUsersModuleSeeder::class);
    }
}
