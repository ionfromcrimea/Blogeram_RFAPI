<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bloger;
use Faker\Generator as Faker;

$factory->define(Bloger::class, function (Faker $faker) {
    return [
        'login' => $faker->name(),
        'password' => $faker->userName,
        'status' => $faker->randomDigit,
    ];
});
