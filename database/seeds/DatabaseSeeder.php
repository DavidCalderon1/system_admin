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
        $this->call(AllActionsPermissionSeeder::class);
        $this->call(UserActionPermissionsSeeder::class);
        $this->call(CreateRolSuperAdmin::class);
        $this->call(UserAdminSeeder::class);
    }
}
