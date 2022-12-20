<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(PrinterSeeder::class);
        $this->call(PrintJobSeeder::class);
        $this->call(ClientApplicationSeeder::class);
        $this->call(PrintJobPromiseSeeder::class);
    }
}
