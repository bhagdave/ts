<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Stream;
use Faker\Generator as Faker;

$factory->define(Stream::class, function (Faker $faker) {
    return [
        'extra_attributes' => Str::random(15),
    ];
});
