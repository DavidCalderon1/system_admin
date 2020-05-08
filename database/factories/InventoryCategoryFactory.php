<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\InventoryCategory;
use Faker\Generator as Faker;


$factory->define(InventoryCategory::class, function (Faker $faker) {
    return [
        'parent_id' => 0,
        'name' => $faker->name,
        'description' => $faker->text,
    ];
});