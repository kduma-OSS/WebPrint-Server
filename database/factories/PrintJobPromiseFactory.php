<?php

namespace Database\Factories;

use App\Models\ClientApplication;
use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\Printer;
use App\Models\PrintJob;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PrintJobPromiseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status'       => PrintJobPromiseStatusEnum::New,
            'name'         => $this->faker->name(),
            'type'         => $this->faker->randomElement(['raw', 'ppd']),
            'ppd_options'  => [],
            'content' => $this->faker->words(asText: true),
            'content_file' => false,
            'file_name' => sprintf('%s.%s', $this->faker->word(), $this->faker->fileExtension()),
            'size'         => $this->faker->randomNumber(),
            'meta'         => $this->faker->words(),

            'client_application_id' => ClientApplication::factory(),
            'print_job_id'          => PrintJob::factory(),
            'printer_id'            => Printer::factory(),
        ];
    }
}
