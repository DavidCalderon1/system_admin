<?php

use Illuminate\Database\Seeder;

class RegisterFaker extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\ThirdParties::class,20)->create();
        factory(\App\Models\Product::class,20)->create();
        factory(\App\Models\Warehouse::class,20)->create();
    }
}
