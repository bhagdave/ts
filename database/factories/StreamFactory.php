<?php

namespace Database\Factories;

use App\Stream;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StreamFactory extends Factory
{
    protected $model = Stream::class;

    public function definition(): array
    {
        return [
            'extra_attributes' => Str::random(15),
        ];
    }
}
