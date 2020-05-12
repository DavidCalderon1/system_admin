<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsPurchasesModuleSeeder
 */
class PermissionsPurchasesModuleSeeder extends Seeder
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
                'name' => 'Purchase List',
                'slug' => 'purchase-list',
            ],
            [
                'name' => 'Purchase View',
                'slug' => 'purchase-view',
            ],
            [
                'name' => 'Purchase Create',
                'slug' => 'purchase-create',
            ],
            [
                'name' => 'Purchase Cancel',
                'slug' => 'purchase-cancel',
            ],
        ];
    }
}
