<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sale;
use Faker\Generator as Faker;

$factory->define(Sale::class, function (Faker $faker) {
    $randomId = rand(1, 48331);

    return [
        'client_id' => function () {
            return factory(\App\Models\ThirdParties::class)->create([
                'type' => 'client'
            ]);
        },
        'prefix' => 'FE',
        'consecutive' => $randomId,
        'client_identity_type' => $faker->randomElement(['CC', 'NIT']),
        'client_identity_number' => $faker->randomNumber(),
        'client_name' => strtoupper($faker->name),
        'client_last_name' => strtoupper($faker->lastName),
        'client_contact' => strtoupper($faker->email),
        'seller_code' => $faker->randomNumber(5,true),
        'date' => $faker->date('Y-m-d'),
        'status' => $faker->randomElement(['Activa', 'Anulada']),
        'description' => $faker->text(50),
        'file' => ''
    ];
});
