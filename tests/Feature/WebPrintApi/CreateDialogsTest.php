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

class CreateDialogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_set_without_token(): void
    {
        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $this
            ->postJson('/api/web-print/promises/'.$promise->ulid.'/dialog', [
                'auto_print' => true,
            ])
            ->assertUnauthorized();
    }

    public function test_cannot_set_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $this
            ->postJson('/api/web-print/promises/'.$promise->ulid.'/dialog', [
                'auto_print' => true,
            ])
            ->assertForbidden();
    }

    public function test_cannot_set_others_dialog(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $this
            ->postJson('/api/web-print/promises/'.$promise->ulid.'/dialog', [
                'auto_print' => true,
            ])
            ->assertForbidden();
    }

    public function test_can_set_with_correct_token(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->for($client)
            ->create();

        $response = $this
            ->postJson(
                '/api/web-print/promises/'.$promise->ulid.'/dialog', [
                    'auto_print' => true,
                ]
            )
            ->assertOk();

        $dialog = $promise->fresh()->PrintDialog;

        $response->assertExactJson([
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

    public function test_can_set_with_correct_token_and_removes_old(): void
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
            ->postJson(
                '/api/web-print/promises/'.$promise->ulid.'/dialog', [
                    'auto_print' => true,
                ]
            )
            ->assertOk();

        $this->assertDatabaseMissing('print_dialogs', [
            'id' => $dialog->id,
        ]);

        $dialog = $promise->fresh()->PrintDialog;

        $response->assertExactJson([
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

    /** @dataProvider correctValues */
    public function test_sets_correct_values(array $post, array $expected): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->for($client)
            ->create();

        $response = $this
            ->postJson(
                '/api/web-print/promises/'.$promise->ulid.'/dialog', $post
            )
            ->assertOk();

        $dialog = $promise->fresh()->PrintDialog;

        $response->assertJson([
            'data' => $expected,
        ]);
    }

    public function correctValues()
    {
        yield 'auto_print' => [
            [
                'auto_print' => false,
            ],
            [
                'auto_print' => false,
                'redirect_url' => null,
                'restricted_ip' => null,
            ],
        ];

        yield 'redirect_url' => [
            [
                'redirect_url' => 'http://example.com/',
            ],
            [
                'auto_print' => true,
                'redirect_url' => 'http://example.com/',
                'restricted_ip' => null,
            ],
        ];

        yield 'restricted_ip' => [
            [
                'restricted_ip' => '8.8.8.8',
            ],
            [
                'auto_print' => true,
                'redirect_url' => null,
                'restricted_ip' => '8.8.8.8',
            ],
        ];

        yield 'all' => [
            [
                'auto_print' => false,
                'restricted_ip' => '8.8.8.8',
                'redirect_url' => 'http://example.com/',
            ],
            [
                'auto_print' => false,
                'redirect_url' => 'http://example.com/',
                'restricted_ip' => '8.8.8.8',
            ],
        ];
    }
}
