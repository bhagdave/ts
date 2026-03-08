<?php

namespace Database\Factories;

use App\Issue;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    protected $model = Issue::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(),
            'attributes' => 'Open',
            'location' => $this->faker->streetAddress,
        ];
    }
}
