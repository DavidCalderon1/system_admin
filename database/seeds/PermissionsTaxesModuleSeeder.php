<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsConfigTransactionsTaxesModuleSeeder
 */
class PermissionsTaxesModuleSeeder extends Seeder
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
                'name' => 'Taxes List',
                'slug' => 'config-taxes-list',
            ],
            [
                'name' => 'Taxes Create',
                'slug' => 'config-taxes-create',
            ],
            [
                'name' => 'Taxes Update',
                'slug' => 'config-taxes-update',
            ],
            [
                'name' => 'Taxes Delete',
                'slug' => 'config-taxes-delete',
            ],
        ];
    }
}
