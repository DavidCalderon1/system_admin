<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tax;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Tax::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'type' => $faker->randomElement(['IVA', 'Retefuente', 'ReteICA', 'ReteIVA', 'Impoconsumo']),
        'percentage' => $faker->randomFloat(1,1,10),
    ];
});
