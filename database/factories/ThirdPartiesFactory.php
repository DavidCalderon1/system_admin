<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ThirdParties;
use Faker\Generator as Faker;

$factory->define(ThirdParties::class, function (Faker $faker) {
    $randomId = rand(1, 48331);
    $city = \App\Models\City::where('id', $randomId)->first();
    $state = \App\Models\State::where('id', $city->state_id)->first();
    $country = \App\Models\Country::where('id', $state->country_id)->first();

    return [
        'country_id' => $country->id,
        'state_id' => $state->id,
        'city_id' => $city->id,
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
