<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetPromiseContentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token(): void
    {
        $promise = PrintJobPromise::factory()->create();

        $this->getJson('/api/web-print/promises/'.$promise->ulid.'/content')
            ->assertUnauthorized();
    }

    public function test_cannot_access_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()->create();

        $this->getJson('/api/web-print/promises/'.$promise->ulid.'/content')
            ->assertForbidden();
    }

    public function test_can_access_with_correct_token(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withContent(1234)
            ->for($client)
            ->create();

        $response = $this->get('/api/web-print/promises/'.$promise->ulid.'/content')
            ->assertOk();

        $this->assertEquals($promise->content, $response->getContent());
    }

    public function test_cant_access_when_no_content_is_available(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->for($client)
            ->create();

        $response = $this->get('/api/web-print/promises/'.$promise->ulid.'/content')
            ->assertNotFound();

        $this->assertEquals($promise->content, $response->getContent());
    }

    public function test_cannot_access_others_promises(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()->create();

        $this->getJson('/api/web-print/promises/'.$promise->ulid.'/content')
            ->assertForbidden();
    }
}
