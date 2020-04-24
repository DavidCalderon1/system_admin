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
        //Crear rol Admin y usuario Administrador
        $this->call(CreateRolSuperAdmin::class);
        $this->call(CreateUserSuperAdminSeeder::class);

        //Permisos de cada modulo crud
        $this->call(CreatePermissionsUsersModuleSeeder::class);
        $this->call(CreatePermissionsRolesModuleSeeder::class);

    }
}
