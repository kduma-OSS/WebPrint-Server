<?php

namespace Database\Factories;

use App\Models\PrintServer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PrinterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'                    => $this->faker->name(),
            'location'                => $this->faker->word(),
            'enabled'                 => $this->faker->boolean(),
            'ppd_support'             => $this->faker->boolean(),
            'ppd_options'             => [],
            'raw_languages_supported' => $this->faker->words(),
            'uri'                     => $this->faker->url(),

            'print_server_id' => PrintServer::factory(),
        ];
    }
}
