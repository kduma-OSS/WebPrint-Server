<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ClientApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'           => $this->faker->name(),
            'last_active_at' => null,

            'team_id' => Team::factory(),
        ];
    }
}
