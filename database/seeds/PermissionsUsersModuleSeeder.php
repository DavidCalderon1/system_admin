<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class UserActionPermissionsSeeder
 */
class PermissionsUsersModuleSeeder extends Seeder
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
                'name' => 'User List',
                'slug' => 'user-list',
            ],
            [
                'name' => 'User Create',
                'slug' => 'user-create',
            ],
            [
                'name' => 'User Update',
                'slug' => 'user-update',
            ],
            [
                'name' => 'User Delete',
                'slug' => 'user-delete',
            ],
        ];
    }
}
