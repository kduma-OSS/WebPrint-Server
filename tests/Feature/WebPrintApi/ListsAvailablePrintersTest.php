<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListsAvailablePrintersTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token(): void
    {
        $this->getJson('/api/web-print/printers')
            ->assertUnauthorized();
    }

    public function test_cannot_access_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $this->getJson('/api/web-print/printers')
            ->assertForbidden();
    }

    public function test_can_access_with_correct_token(): void
    {
        Sanctum::actingAs(
            ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $this->getJson('/api/web-print/printers')
            ->assertOk()
            ->assertExactJson([
                'data' => [],
            ]);
    }

    public function test_lists_printers(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $server = PrintServer::factory()
            ->recycle($client->Team)
            ->create();

        $printers = Printer::factory()
            ->active()
            ->for($server, 'Server')
            ->count(1)
            ->create();

        $client->Printers()->attach($printers);
        $printer = $printers->first();

        $this->getJson('/api/web-print/printers')
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    [
                        'location' => $printer->location,
                        'name' => $printer->name,
                        'ppd_support' => $printer->ppd_support,
                        'raw_languages_supported' => $printer->raw_languages_supported,
                        'server' => [
                            'name' => $server->name,
                            'ulid' => $server->ulid,
                        ],
                        'ulid' => $printer->ulid,
                    ],
                ],
            ]);
    }

    public function test_lists_printers_with_ppd(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $server = PrintServer::factory()
            ->recycle($client->Team)
            ->create();

        $printers = Printer::factory()
            ->active()
            ->for($server, 'Server')
            ->count(1)
            ->create();

        $client->Printers()->attach($printers);
        $printer = $printers->first();

        $this->getJson('/api/web-print/printers?ppd_options=true')
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    [
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
                ],
            ]);
    }

    public function test_lists_printers_of_ppd_type(): void
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
            ->ppd()
            ->for($server, 'Server')
            ->create();

        $client->Printers()->attach($printer);

        $printer_other = Printer::factory()
            ->active()
            ->noPpd()
            ->for($server, 'Server')
            ->create();

        $client->Printers()->attach($printer_other);

        $this->getJson('/api/web-print/printers?type=ppd')
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    [
                        'location' => $printer->location,
                        'name' => $printer->name,
                        'ppd_support' => $printer->ppd_support,
                        'raw_languages_supported' => $printer->raw_languages_supported,
                        'server' => [
                            'name' => $server->name,
                            'ulid' => $server->ulid,
                        ],
                        'ulid' => $printer->ulid,
                    ],
                ],
            ]);
    }

    public function test_lists_printers_of_raw_type(): void
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
            ->type(['pcl', 'escpos'])
            ->for($server, 'Server')
            ->create();

        $client->Printers()->attach($printer);

        $printer_other = Printer::factory()
            ->active()
            ->type(['pcl', 'zpl'])
            ->for($server, 'Server')
            ->create();

        $client->Printers()->attach($printer_other);

        $this->getJson('/api/web-print/printers?type=escpos')
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    [
                        'location' => $printer->location,
                        'name' => $printer->name,
                        'ppd_support' => $printer->ppd_support,
                        'raw_languages_supported' => $printer->raw_languages_supported,
                        'server' => [
                            'name' => $server->name,
                            'ulid' => $server->ulid,
                        ],
                        'ulid' => $printer->ulid,
                    ],
                ],
            ]);
    }

    public function test_cannot_lists_others_printers(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $my_server = PrintServer::factory()
            ->recycle($client->Team)
            ->create();

        $my_printers = Printer::factory()
        ->active()
            ->for($my_server, 'Server')
            ->count(2)
            ->create();

        $others_server = PrintServer::factory()
            ->create();

        $others_printers = Printer::factory()
        ->active()
            ->for($others_server, 'Server')
            ->count(2)
            ->create();

        $client->Printers()->attach($my_printers);

        $response = $this->getJson('/api/web-print/printers')
            ->assertOk();

        $content = $response->content();

        $my_printers->each(function (Printer $p) use ($content): void {
            $this->assertStringContainsStringIgnoringCase($p->ulid, $content);
        });
        $others_printers->each(function (Printer $p) use ($content): void {
            $this->assertStringNotContainsStringIgnoringCase($p->ulid, $content);
        });
    }

    public function test_cannot_lists_printers_not_attached_to_print_server(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $server = PrintServer::factory()
            ->recycle($client->Team)
            ->create();

        $associated_printers = Printer::factory()
            ->active()
            ->for($server, 'Server')
            ->count(2)
            ->create();

        $others_printers = Printer::factory()
        ->active()
            ->for($server, 'Server')
            ->count(2)
            ->create();

        $client->Printers()->attach($associated_printers);

        $response = $this->getJson('/api/web-print/printers')
            ->assertOk();

        $content = $response->content();

        $associated_printers->each(function (Printer $p) use ($content): void {
            $this->assertStringContainsStringIgnoringCase($p->ulid, $content);
        });
        $others_printers->each(function (Printer $p) use ($content): void {
            $this->assertStringNotContainsStringIgnoringCase($p->ulid, $content);
        });
    }

    public function test_cant_see_disabled_printers(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $server = PrintServer::factory()
            ->recycle($client->Team)
            ->create();

        $printer = Printer::factory()
            ->inactive()
            ->for($server, 'Server')
            ->create();

        $client->Printers()->attach($printer);

        $this->getJson('/api/web-print/printers')
            ->assertOk()
            ->assertExactJson([
                'data' => [],
            ]);
    }
}
