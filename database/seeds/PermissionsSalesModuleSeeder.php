<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsSalesModuleSeeder
 */
class PermissionsSalesModuleSeeder extends Seeder
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
                'name' => 'Sale List',
                'slug' => 'sale-list',
            ],
            [
                'name' => 'Sale View',
                'slug' => 'sale-view',
            ],
            [
                'name' => 'Sale Create',
                'slug' => 'sale-create',
            ],
            [
                'name' => 'Sale Cancel',
                'slug' => 'sale-cancel',
            ],
        ];
    }
}
