<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\News;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'news' => $faker->text(50),
        'image' => $faker->word,
        'list1' => $faker->word,
    ];
});
