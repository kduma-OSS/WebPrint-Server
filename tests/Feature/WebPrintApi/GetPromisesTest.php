<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetPromisesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token(): void
    {
        $promise = PrintJobPromise::factory()->create();

        $this->getJson('/api/web-print/promises/'.$promise->ulid)
            ->assertUnauthorized();
    }

    public function test_cannot_access_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()->create();

        $this->getJson('/api/web-print/promises/'.$promise->ulid)
            ->assertForbidden();
    }

    public function test_can_access_with_correct_token(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withContent()
            ->for($client)
            ->create();

        $this->getJson('/api/web-print/promises/'.$promise->ulid)
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'ulid' => $promise->ulid,
                    'available_printers' => [],
                    'content_available' => true,
                    'created_at' => $promise->created_at,
                    'file_name' => $promise->file_name,
                    'job' => [
                        'ulid' => $promise->PrintJob->ulid,
                        'name' => $promise->PrintJob->name,
                        'created_at' => $promise->PrintJob->created_at,
                        'file_name' => $promise->PrintJob->file_name,
                        'ppd' => $promise->PrintJob->ppd,
                        'size' => $promise->PrintJob->size,
                        'status' => $promise->PrintJob->status,
                        'status_message' => $promise->PrintJob->status_message,
                        'updated_at' => $promise->PrintJob->updated_at,
                    ],
                    'meta' => $promise->meta,
                    'name' => $promise->name,
                    'ppd_options' => $promise->ppd_options,
                    'selected_printer' => [
                        'ulid' => $promise->Printer->ulid,
                        'name' => $promise->Printer->name,
                    ],
                    'size' => $promise->size,
                    'status' => $promise->status,
                    'type' => $promise->type,
                    'updated_at' => $promise->updated_at,
                ],
            ]);
    }

    public function test_cannot_access_others_promises(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()->create();

        $this->getJson('/api/web-print/promises/'.$promise->ulid)
            ->assertForbidden();
    }
}
