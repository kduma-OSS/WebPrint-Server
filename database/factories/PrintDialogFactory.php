<?php

namespace Database\Factories;

use App\Models\PrintJobPromise;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintDialogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => 'new',
            'auto_print' => $this->faker->boolean(),
            'redirect_url' => $this->faker->url(),
            'restricted_ip' => $this->faker->ipv4(),

            'print_job_promise_id' => PrintJobPromise::factory(),
        ];
    }
}
