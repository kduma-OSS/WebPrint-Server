<?php

namespace Database\Seeders;

use App\Models\ClientApplication;
use App\Models\Printer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class ClientApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new ClientApplication;
        $client->name = 'Test Debug Client';
        $client->save();

        $client->tokens()->create([
            'name' => 'Test Debug Client',
            'token' => hash('sha256', $plainTextToken = 'TEST_DEBUG_CLIENT'),
            'abilities' => ['*'],
        ]);

        /** @var Printer $printer */
        $printer = Printer::query()->firstWhere([
            'name' => 'DEBUG',
        ]);

        $client->Printers()->attach($printer);




        $client = new ClientApplication;
        $client->name = 'Test Working Client';
        $client->save();

        $client->tokens()->create([
            'name' => 'Test Working Client',
            'token' => hash('sha256', $plainTextToken = 'TEST_WORKING_CLIENT'),
            'abilities' => ['*'],
        ]);

        /** @var Collection $printers */
        $printers = Printer::query()->where('name', '!=', 'DEBUG')->get();

        $client->Printers()->attach($printers);
    }
}
