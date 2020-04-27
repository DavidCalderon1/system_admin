<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
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
        $this->call(UserSuperAdminSeeder::class);

        //Agregar Paises, Estados y Ciudades
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);

        //Permisos de cada modulo crud
        $this->call(PermissionsUsersModuleSeeder::class);
        $this->call(PermissionsRolesModuleSeeder::class);
        $this->call(PermissionsThirdsModuleSeeder::class);
        $this->call(PermissionsInventoryCategoryModuleSeeder::class);
        $this->call(PermissionsWarehousesModuleSeeder::class);
    }
}
