<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsConfigTransactionsTaxesModuleSeeder
 */
class PermissionsConfigTaxesModuleSeeder extends Seeder
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
                'name' => 'Config Taxes List',
                'slug' => 'config-taxes-list',
            ],
            [
                'name' => 'Config Taxes Create',
                'slug' => 'config-taxes-create',
            ],
            [
                'name' => 'Config Taxes Update',
                'slug' => 'config-taxes-update',
            ],
            [
                'name' => 'Config Taxes Delete',
                'slug' => 'config-taxes-delete',
            ],
        ];
    }
}
