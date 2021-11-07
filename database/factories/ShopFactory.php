<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Shop;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Shop::class, function (Faker $faker) {
    $name = $faker->unique()->domainWord;
    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'logo_image' => $faker->randomElement([
            '1.png',
            '2.png',
            '3.png',
            '4.png',
            '5.png',
            '6.png',
        ]),
        'address' => $faker->address,
    ];
});
