<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SaleProduct;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Sale;
use Faker\Generator as Faker;

$factory->define(SaleProduct::class, function (Faker $faker) {
    $base_price = round($faker->randomFloat(), 2);
    return [
        'sale_id' => function () {
            return factory(Sale::class)->create();
        },
        'product_id' => function () {
            return factory(Product::class)->create();
        },
        'warehouse_id' =>  function () {
            return factory(Warehouse::class)->create();
        },
        'name' => $faker->name,
        'quantity' => $faker->randomNumber(1, true),
        'price' => round($base_price * 2, 2),
        'discount_percentage'=>$faker->randomElement([5,10,15,20,25,30,35,40,45,50]),
        'vat' => $faker->randomElement(['0', '5', '19']),
        'description' => $faker->text(50),
    ];
});
