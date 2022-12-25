<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\PrintDialog;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeletePromisesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_delete_without_token(): void
    {
        $promise = PrintJobPromise::factory()
            ->create();

        $this
            ->deleteJson('/api/web-print/promises/'.$promise->ulid)
            ->assertUnauthorized();

        $this->assertDatabaseHas('print_job_promises', [
            'id' => $promise->id,
        ]);
    }

    public function test_cannot_delete_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->create();

        $this
            ->deleteJson('/api/web-print/promises/'.$promise->ulid)
            ->assertForbidden();

        $this->assertDatabaseHas('print_job_promises', [
            'id' => $promise->id,
        ]);
    }

    public function test_cannot_delete_others_promises(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->create();

        $this
            ->deleteJson('/api/web-print/promises/'.$promise->ulid)
            ->assertForbidden();

        $this->assertDatabaseHas('print_job_promises', [
            'id' => $promise->id,
        ]);
    }

    public function test_can_delete_with_correct_token(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->for($client)
            ->create();

        PrintDialog::factory()
            ->for($promise, 'JobPromise')
            ->create();

        $this
            ->deleteJson(
                '/api/web-print/promises/'.$promise->ulid
            )
            ->assertNoContent();

        $this->assertDatabaseHas('print_job_promises', [
            'id' => $promise->id,
            'status' => 'cancelled',
        ]);
    }
}
