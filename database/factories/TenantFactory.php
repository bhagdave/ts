<?php

namespace Database\Factories;

use App\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'sub' => 'fake_user' . Str::random(15),
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
