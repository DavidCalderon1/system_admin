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
        'code' => $faker->word,
        'reference' => $faker->name,
        'description' => $faker->text,
        'base_price' => $faker->randomFloat(),
        'vat' => $faker->randomNumber(),
        'price' => $faker->randomFloat(),
        'image' => $faker->image(),
    ];
});
