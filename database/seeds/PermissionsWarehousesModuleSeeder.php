<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsThirdsModuleSeeder
 */
class PermissionsWarehousesModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert($this->data());
    }

    /**
     * @return array|string[][]
     */
    public function data(): array
    {
        return [
            [
                'name' => 'Warehouse List',
                'slug' => 'warehouse-list',
            ],
            [
                'name' => 'Warehouse Create',
                'slug' => 'warehouse-create',
            ],
            [
                'name' => 'Warehouse Update',
                'slug' => 'warehouse-update',
            ],
            [
                'name' => 'Warehouse Delete',
                'slug' => 'warehouse-delete',
            ],
        ];
    }
}
