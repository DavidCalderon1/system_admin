<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Warehouse;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Warehouse::class, function (Faker $faker) {

    $randomId = rand(1, 48331);
    $city = City::where('id', $randomId)->first();
    $state = State::where('id', $city->state_id)->first();
    $country = Country::where('id', $state->country_id)->first();

    return [
        'name' => strtoupper($faker->name),
        'country_id' => $country->id,
        'state_id' => $state->id,
        'city_id' => $city->id,
        'address' => strtoupper($faker->address),
        'phone_number' => '3146695000',
    ];
});
