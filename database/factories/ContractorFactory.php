<?php

namespace Database\Factories;

use App\Contractor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContractorFactory extends Factory
{
    protected $model = Contractor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'company' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'notes' => Str::random(20),
        ];
    }
}
