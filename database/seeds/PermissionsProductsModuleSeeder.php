<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsProductsModuleSeeder
 */
class PermissionsProductsModuleSeeder extends Seeder
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
                'name' => 'Products List',
                'slug' => 'products-list',
            ],
            [
                'name' => 'Products Create',
                'slug' => 'products-create',
            ],
            [
                'name' => 'Products Update',
                'slug' => 'products-update',
            ],
            [
                'name' => 'Products Delete',
                'slug' => 'products-delete',
            ],
        ];
    }
}
