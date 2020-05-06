<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\Models\InventoryCategory;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'category_id' => function () {
            return factory(InventoryCategory::class)->create();
        },
        'code' => $faker->randomNumber(6),
        'reference' => $faker->name,
        'description' => $faker->text,
        'base_price' => $faker->randomFloat(),
        'vat' => $faker->randomElement(['0','5','19']),
        'price' => $faker->randomFloat(),
        'image' => $faker->imageUrl(),
    ];
});
