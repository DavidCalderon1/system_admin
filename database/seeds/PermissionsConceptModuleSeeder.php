<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionsConceptModuleSeeder
 */
class PermissionsConceptModuleSeeder extends Seeder
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
                'name' => 'Concept List',
                'slug' => 'concept-list',
            ],
            [
                'name' => 'Concept Create',
                'slug' => 'concept-create',
            ],
            [
                'name' => 'Concept Update',
                'slug' => 'concept-update',
            ],
            [
                'name' => 'Concept Delete',
                'slug' => 'concept-delete',
            ],
        ];
    }
}
