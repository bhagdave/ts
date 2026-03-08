<?php

namespace Database\Factories;

use App\Agency;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgencyFactory extends Factory
{
    protected $model = Agency::class;

    public function definition(): array
    {
        return [
            'company_name' => $this->faker->name,
            'trial_ends_at' => now()->addDays(30),
        ];
    }
}
