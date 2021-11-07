<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use \Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    $name = $faker->unique()->sentence(2);
    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'description' => $faker->sentence,
        'category_id' => mt_rand(1, 4),
        'status' => 1
    ];
});
