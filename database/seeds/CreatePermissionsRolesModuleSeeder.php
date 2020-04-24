<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CreatePermissionsRolesModuleSeeder
 */
class CreatePermissionsRolesModuleSeeder extends Seeder
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
                'name' => 'Role List',
                'slug' => 'role-list',
            ],
            [
                'name' => 'Role Create',
                'slug' => 'role-create',
            ],
            [
                'name' => 'Role Update',
                'slug' => 'role-update',
            ],
            [
                'name' => 'Role Delete',
                'slug' => 'role-delete',
            ],
        ];
    }
}
