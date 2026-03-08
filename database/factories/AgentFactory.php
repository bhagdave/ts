<?php

namespace Database\Factories;

use App\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentFactory extends Factory
{
    protected $model = Agent::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'user_id' => 0,
        ];
    }
}
