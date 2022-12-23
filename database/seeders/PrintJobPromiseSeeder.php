<?php

namespace Database\Seeders;

use App\Models\ClientApplication;
use App\Models\PrintJobPromise;
use Illuminate\Database\Seeder;

class PrintJobPromiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = ClientApplication::firstWhere(['name' => 'Test Debug Client']);

        $promise = new PrintJobPromise;
        $promise->client_application_id = $client->id;
        $promise->printer_id = $client->Printers->first()->id;
        $promise->status = 'new';
        $promise->name = 'Test Promise';
        $promise->type = 'ppd';

        $promise->save();

        $promise->AvailablePrinters()->sync($client->Printers);

        $client = ClientApplication::firstWhere(['name' => 'Test Local Client']);

        $promise = new PrintJobPromise;
        $promise->client_application_id = $client->id;
        $promise->printer_id = $client->Printers->first()->id;
        $promise->status = 'new';
        $promise->name = 'Test 2 Promise';
        $promise->type = 'ppd';

        $promise->save();

        $promise->AvailablePrinters()->sync($client->Printers);
    }
}
