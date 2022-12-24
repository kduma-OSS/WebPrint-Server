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

class GetDialogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_get_without_token(): void
    {
        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $dialog = PrintDialog::factory()
            ->for($promise, 'JobPromise')
            ->create();

        $this
            ->getJson('/api/web-print/promises/'.$promise->ulid.'/dialog')
            ->assertUnauthorized();
    }

    public function test_cannot_get_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $dialog = PrintDialog::factory()
            ->for($promise, 'JobPromise')
            ->create();

        $this
            ->getJson('/api/web-print/promises/'.$promise->ulid.'/dialog')
            ->assertForbidden();
    }

    public function test_cannot_get_others_dialog(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $dialog = PrintDialog::factory()
            ->for($promise, 'JobPromise')
            ->create();

        $this
            ->getJson('/api/web-print/promises/'.$promise->ulid.'/dialog')
            ->assertForbidden();
    }

    public function test_cant_get_non_existing_with_correct_token(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->for($client)
            ->create();

        $response = $this->getJson(
            '/api/web-print/promises/'.$promise->ulid.'/dialog'
        )->assertNotFound();
    }

    public function test_can_get_with_correct_token_as_json(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->for($client)
            ->create();

        $dialog = PrintDialog::factory()
            ->for($promise, 'JobPromise')
            ->create();

        $response = $this
            ->getJson(
                '/api/web-print/promises/'.$promise->ulid.'/dialog'
            )
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'auto_print' => $dialog->auto_print,
                    'created_at' => $dialog->created_at,
                    'redirect_url' => $dialog->redirect_url,
                    'restricted_ip' => $dialog->restricted_ip,
                    'status' => $dialog->status,
                    'ulid' => $dialog->ulid,
                    'updated_at' => $dialog->updated_at,
                    'link' => $dialog->link,
                ],
            ]);
    }
}
