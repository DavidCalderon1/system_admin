<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsThirdsModuleSeeder
 */
class PermissionsThirdsModuleSeeder extends Seeder
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
                'name' => 'Thirds List',
                'slug' => 'third-list',
            ],
            [
                'name' => 'Thirds Create',
                'slug' => 'third-create',
            ],
            [
                'name' => 'Thirds Update',
                'slug' => 'third-update',
            ],
            [
                'name' => 'Thirds Delete',
                'slug' => 'third-delete',
            ],
        ];
    }
}
