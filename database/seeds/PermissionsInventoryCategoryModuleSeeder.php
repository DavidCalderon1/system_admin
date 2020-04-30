<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsInventoryCategoryModuleSeeder
 */
class PermissionsInventoryCategoryModuleSeeder extends Seeder
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
                'name' => 'Inventory Category List',
                'slug' => 'inventory-category-list',
            ],
            [
                'name' => 'Inventory Category Create',
                'slug' => 'inventory-category-create',
            ],
            [
                'name' => 'Inventory Category Update',
                'slug' => 'inventory-category-update',
            ],
            [
                'name' => 'Inventory Category Delete',
                'slug' => 'inventory-category-delete',
            ],
        ];
    }
}
