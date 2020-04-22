<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class AllActionsPermisionSeeder
 */
class AllActionsPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'All Actions',
                'slug' => 'all-actions',
            ]
        ]);
    }
}
