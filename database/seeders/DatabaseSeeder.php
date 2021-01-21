<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PrinterSeeder::class);
//        $this->call(PrintJobSeeder::class);
        $this->call(ClientApplicationSeeder::class);
//        $this->call(PrintJobPromiseSeeder::class);

        // \App\Models\User::factory(10)->create();
    }
}
