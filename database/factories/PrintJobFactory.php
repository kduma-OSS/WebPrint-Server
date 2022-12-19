<?php

namespace Database\Factories;

use App\Models\ClientApplication;
use App\Models\Enums\PrintJobStatusEnum;
use App\Models\Printer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintJobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => PrintJobStatusEnum::New,
            'status_message' => null,
            'name' => $this->faker->name(),
            'ppd' => $this->faker->boolean(),
            'ppd_options' => [],
            'content' => $this->faker->words(asText: true),
            'content_file' => false,
            'file_name' => sprintf('%s.%s', $this->faker->word(), $this->faker->fileExtension()),
            'size' => $this->faker->randomNumber(),

            'printer_id' => Printer::factory(),
            'client_application_id' => ClientApplication::factory(),
        ];
    }
}
