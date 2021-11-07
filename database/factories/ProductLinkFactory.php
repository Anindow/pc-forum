<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductLink;
use Faker\Generator as Faker;

$factory->define(ProductLink::class, function (Faker $faker) {
    return [
        'shop_id' => mt_rand(1, 10),
        'product_id' => mt_rand(1, 10),
    ];
});

