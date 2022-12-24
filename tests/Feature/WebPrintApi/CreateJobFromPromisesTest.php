<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateJobFromPromisesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_create_without_token(): void
    {
        $promise = PrintJobPromise::factory()
            ->create();

        $this
            ->postJson('/api/web-print/jobs', [
                'promise' => $promise->ulid,
            ])
            ->assertUnauthorized();
    }

    public function test_cannot_create_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->create();

        $this
            ->postJson('/api/web-print/jobs', [
                'promise' => $promise->ulid,
            ])
            ->assertForbidden();
    }

    public function test_cannot_create_others_promises(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->create();

        $this
            ->postJson('/api/web-print/jobs', [
                'promise' => $promise->ulid,
            ])
            ->assertForbidden();
    }

    public function test_can_create_with_correct_token(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->for($client)
            ->create();

        $response = $this
            ->postJson(
                '/api/web-print/jobs', [
                    'promise' => $promise->ulid,
                ]
            )
            ->assertNoContent();

        $this->assertDatabaseHas('print_job_promises', [
            'id' => $promise->id,
            'status' => 'sent_to_printer',
        ]);

        $this->assertDatabaseHas('print_jobs', [
            'id' => $promise->print_job_id,
            'status' => 'new',
        ]);
    }

    public function test_cant_create_duplicate(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->for($client)
            ->create([
                'status' => PrintJobPromiseStatusEnum::SentToPrinter,
            ]);

        $response = $this
            ->postJson(
                '/api/web-print/jobs', [
                    'promise' => $promise->ulid,
                ]
            )
            ->assertStatus(412);
    }
}
