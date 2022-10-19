<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Properties;
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

$factory->define(Properties::class, function (Faker $faker) {
    return [
        'propertyName' => 'TEST_' . Str::random(15),
        'inputAddress' => $faker->streetAddress,
        'inputCity' => $faker->city,
        'inputPostcode' => $faker->postcode,
        'propertyLat' => $faker->latitude(),
        'propertyLng' => $faker->longitude(),
        'propertyType' => 'Social Housing',
        'created_by_user_id' => 0
    ];
});
