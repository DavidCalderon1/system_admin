<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\Models\InventoryCategory;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    $base_price = round($faker->randomFloat(), 2);
    return [
        'category_id' => function () {
            return factory(InventoryCategory::class)->create();
        },
        'code' => $faker->randomNumber(6),
        'reference' => $faker->name,
        'description' => $faker->text,
        'base_price' => $base_price,
        'vat' => $faker->randomElement(['0', '5', '19']),
        'price' => round($base_price * 2, 2),
        'image' => $faker->imageUrl(),
    ];
});
