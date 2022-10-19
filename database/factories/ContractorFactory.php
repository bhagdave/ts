<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contractor;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Contractor::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'company' => $faker->name,
        'phone' => $faker->phoneNumber,
        'notes' => Str::random(20)
    ];
});
