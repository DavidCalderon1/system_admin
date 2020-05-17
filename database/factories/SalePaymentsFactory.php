<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sale;
use App\Models\SalePayment;
use Faker\Generator as Faker;

$factory->define(SalePayment::class, function (Faker $faker) {
    $base_price = round($faker->randomFloat(), 2);
    return [
        'sale_id' => function () {
            return factory(Sale::class)->create();
        },
        'way_to_pay' => $faker->randomElement(['credit','cash']),
        'amount' => $faker->randomFloat(2),
        'method' => $faker->randomElement(['Efectivo', 'Tarjeta de crédito', 'Tarjeta débito']),
        'days_to_pay'=>$faker->randomElement([15,30,90,180,360]),
        'credit_expiration_date' => $faker->dateTime,
        'date' => $faker->date()
    ];
});
