<?php

namespace Database\Factories;

use App\Properties;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertiesFactory extends Factory
{
    protected $model = Properties::class;

    public function definition(): array
    {
        return [
            'propertyName' => 'TEST_' . Str::random(15),
            'inputAddress' => $this->faker->streetAddress,
            'inputCity' => $this->faker->city,
            'inputPostcode' => $this->faker->postcode,
            'propertyLat' => $this->faker->latitude(),
            'propertyLng' => $this->faker->longitude(),
            'propertyType' => 'Social Housing',
            'created_by_user_id' => 0,
        ];
    }
}
