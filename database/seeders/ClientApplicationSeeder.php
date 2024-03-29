<?php

namespace Database\Seeders;

use App\Models\ClientApplication;
use App\Models\PrintServer;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClientApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = new ClientApplication();
        $client->team_id = Team::where('personal_team', false)->firstOrFail('id')->id;
        $client->name = 'Test Debug Client';
        $client->save();
        $token = $client->tokens()->create([
            'name' => sprintf('%s Access Token', $client->name),
            'token' => hash('sha256', $plainTextToken = 'TEST_DEBUG_CLIENT'),
            'abilities' => ['*'],
        ]);
        echo sprintf("\n\n\n%s=\"%s|%s\"\n\n\n", strtoupper(Str::slug(sprintf('%s Access Token', $client->name), '_')), $token->id, $plainTextToken);
        $print_server = PrintServer::query()->firstWhere([
            'name' => 'Debug Print Server',
        ]);
        $client->Printers()->attach($print_server->Printers);

        $client = new ClientApplication();
        $client->team_id = Team::where('personal_team', false)->firstOrFail('id')->id;
        $client->name = 'Test Local Client';
        $client->save();
        $token = $client->createToken(sprintf('%s Access Token', $client->name));
        echo sprintf("\n\n\n%s=\"%s\"\n\n\n", strtoupper(Str::slug($token->accessToken->name, '_')), $token->plainTextToken);
        $print_server = PrintServer::query()->firstWhere([
            'name' => 'Local Print Server',
        ]);
        $client->Printers()->attach($print_server->Printers);

        $client = new ClientApplication();
        $client->team_id = Team::where('personal_team', false)->firstOrFail('id')->id;
        $client->name = 'Test LAN Client';
        $client->save();
        $token = $client->createToken(sprintf('%s Access Token', $client->name));
        echo sprintf("\n\n\n%s=\"%s\"\n\n\n", strtoupper(Str::slug($token->accessToken->name, '_')), $token->plainTextToken);
        $print_server = PrintServer::query()->firstWhere([
            'name' => 'LAN Print Server',
        ]);
        $client->Printers()->attach($print_server->Printers);
    }
}
