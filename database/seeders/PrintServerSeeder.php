<?php

namespace Database\Seeders;

use App\Models\PrintServer;
use Illuminate\Database\Seeder;

class PrintServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $server = new PrintServer;
        $server->name = 'Local Print Server';
        $server->save();

        $server->tokens()->create([
            'name' => 'Connector Service Key',
            'token' => hash('sha256', $plainTextToken = 'DEBUG_WEBPRINT_SERVICE_KEY'),
            'abilities' => ['*'],
        ]);
    }
}
