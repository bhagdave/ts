<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Tenant;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Tenant::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'sub' => 'fake_user' . Str::random(15),
        'phone' => $faker->phoneNUmber
    ];
});
