<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\State;
use Faker\Generator as Faker;

$factory->define(State::class, function (Faker $faker) {
    return [
        'name' => $faker->state,
        'country_id' => function () {
            return factory(\App\Models\Country::class)->create();
        }
    ];
});
