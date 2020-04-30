<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'code' => $faker->countryCode,
        'name' => $faker->country,
        'phone_code' => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
    ];
});
