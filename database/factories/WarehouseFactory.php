<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Warehouse;
use Faker\Generator as Faker;


$factory->define(Warehouse::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'country_id' => $faker->randomNumber(2),
        'state_id' => $faker->randomNumber(2),
        'city_id' => $faker->randomNumber(2),
        'address' => $faker->address,
        'phone_number' => '3146695000',
    ];
});
