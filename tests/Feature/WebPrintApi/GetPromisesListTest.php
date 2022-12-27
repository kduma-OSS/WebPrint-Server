<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\PrintJobPromise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetPromisesListTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token(): void
    {
        $this->getJson('/api/web-print/promises')
            ->assertUnauthorized();
    }

    public function test_cannot_access_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $this->getJson('/api/web-print/promises')
            ->assertForbidden();
    }

    public function test_can_access_with_correct_token(): void
    {
        Sanctum::actingAs(
            ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $this->getJson('/api/web-print/promises')
            ->assertOk()
            ->assertJson([
                'data' => [],
            ]);
    }

    public function test_lists_promises(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promises = PrintJobPromise::factory()
            ->withContent()
            ->for($client)
            ->count(3)
            ->create();

        $response = $this->getJson('/api/web-print/promises')
            ->assertOk();

        $promises->map(fn (PrintJobPromise $promise): array => [
            'ulid' => $promise->ulid,
            'status' => $promise->status,
            'content_available' => true,
            'created_at' => $promise->created_at,
            'file_name' => $promise->file_name,
            'meta' => $promise->meta,
            'name' => $promise->name,
            'ppd_options' => $promise->ppd_options,
            'selected_printer' => [
                'ulid' => $promise->Printer->ulid,
                'name' => $promise->Printer->name,
            ],
            'size' => $promise->size,
            'type' => $promise->type,
            'updated_at' => $promise->updated_at,
        ])->each(fn (array $expected) => $response->assertJsonFragment($expected));
    }

    public function test_cannot_lists_others_promises(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $my_promises = PrintJobPromise::factory()
            ->for($client)
            ->count(3)
            ->create();

        $others_promises = PrintJobPromise::factory()
            ->count(3)
            ->create();

        $response = $this->getJson('/api/web-print/promises')
            ->assertOk();

        $content = $response->content();

        $my_promises->each(function (PrintJobPromise $p) use ($content): void {
            $this->assertStringContainsStringIgnoringCase($p->ulid, $content);
        });
        $others_promises->each(function (PrintJobPromise $p) use ($content): void {
            $this->assertStringNotContainsStringIgnoringCase($p->ulid, $content);
        });
    }
}
