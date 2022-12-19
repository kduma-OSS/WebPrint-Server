<?php

namespace Database\Factories;

use App\Models\PrintServer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintServerFactory extends Factory
{
    protected $model = PrintServer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'last_active_at' => null,

            'team_id' => Team::factory(),
        ];
    }
}
