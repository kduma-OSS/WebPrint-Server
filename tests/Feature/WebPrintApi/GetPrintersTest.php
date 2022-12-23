<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetPrintersTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token(): void
    {
        $printer = Printer::factory()
             ->create();

        $this->getJson('/api/web-print/printers/'.$printer->ulid)
            ->assertUnauthorized();
    }

    public function test_cannot_access_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $printer = Printer::factory()
             ->create();

        $this->getJson('/api/web-print/printers/'.$printer->ulid)
            ->assertForbidden();
    }

    public function test_can_access_with_correct_token(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $server = PrintServer::factory()
            ->recycle($client->Team)
            ->create();

        $printer = Printer::factory()
            ->active()
            ->for($server, 'Server')
            ->create();

        $client->Printers()->attach($printer);

        $this->getJson('/api/web-print/printers/'.$printer->ulid)
            ->assertOk()
            ->assertExactJson([
                'data' =>                     [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'ppd_options' => $printer->ppd_options,
                    'ppd_options_layout' => $printer->ppd_options_layout,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'server' => [
                        'name' => $server->name,
                        'ulid' => $server->ulid,
                    ],
                    'ulid' => $printer->ulid,
                ],
            ]);
    }

    public function test_cant_access_someone_else_printer(): void
    {
        Sanctum::actingAs(
            ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $printer = Printer::factory()
             ->create();

        $this->getJson('/api/web-print/printers/'.$printer->ulid)
            ->assertForbidden();
    }
}
