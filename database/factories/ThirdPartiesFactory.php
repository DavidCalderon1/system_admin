<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ThirdParties;
use Faker\Generator as Faker;

$factory->define(ThirdParties::class, function (Faker $faker) {
    return [
        'country_id' => 1,
        'state_id' => 1,
        'city_id' =>1,
        'type' => $faker->randomElement(['client', 'provider', 'other']),
        'identity_number' => $faker->randomNumber(),
        'identity_type' => $faker->randomElement(['CC', 'NIT']),
        'type_person' => $faker->randomElement(['natural', 'juridical']),
        'name' => strtoupper($faker->name),
        'last_name' => strtoupper($faker->lastName),
        'address' => strtoupper($faker->address),
        'phone_number' => '3146695000',
        'phone_extension' => $faker->numberBetween(1, 5),
        'email' => strtoupper($faker->email),
        'description' => $faker->text,
    ];
});
