<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsConfigCostCenterModuleSeeder
 */
class PermissionsCostCenterModuleSeeder extends Seeder
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
                'name' => 'Cost Center List',
                'slug' => 'cost-center-list',
            ],
            [
                'name' => 'Cost Center Create',
                'slug' => 'cost-center-create',
            ],
            [
                'name' => 'Cost Center Update',
                'slug' => 'cost-center-update',
            ],
            [
                'name' => 'Cost Center Delete',
                'slug' => 'cost-center-delete',
            ],
        ];
    }
}
