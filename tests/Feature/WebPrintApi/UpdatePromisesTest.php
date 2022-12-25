<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\Printer;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdatePromisesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_update_without_token(): void
    {
        $promise = PrintJobPromise::factory()->create();

        $this
            ->putJson('/api/web-print/promises/'.$promise->ulid, [
                'name' => 'Test Print',
                'type' => 'ppd',
            ])
            ->assertUnauthorized();
    }

    public function test_cannot_update_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()->create();

        $this
            ->putJson('/api/web-print/promises/'.$promise->ulid, [
                'name' => 'Test Print',
                'type' => 'ppd',
            ])
            ->assertForbidden();
    }

    public function test_cannot_update_others_promise(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()->create();

        $this
            ->putJson('/api/web-print/promises/'.$promise->ulid, [
                'name' => 'Test Print',
                'type' => 'ppd',
            ])
            ->assertForbidden();
    }

    /**
     * @dataProvider providesDataForUpdate
     */
    public function test_can_update_with_correct_token(callable $post, callable $asserts, bool $withContent = true): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $server = PrintServer::factory()
            ->recycle($client->Team)
            ->create();

        $printers_ppd = Printer::factory()
            ->active()
            ->ppd()
            ->for($server, 'Server')
            ->count(2)
            ->create();

        $client->Printers()->attach($printers_ppd);

        if ($withContent) {
            $promise = PrintJobPromise::factory()
                ->withContent()
                ->for($client)
                ->create();
        } else {
            $promise = PrintJobPromise::factory()
                ->withoutContent()
                ->for($client)
                ->create();
        }

        $promise->Printer()->associate($printers_ppd->first());
        $promise->AvailablePrinters()->attach($printers_ppd);

        $response = $this
            ->putJson(
                '/api/web-print/promises/'.$promise->ulid, $post($printers_ppd, $this)
            )
            ->assertNoContent();

        $promise = $promise->fresh();

        $asserts($promise, $response, $printers_ppd, $this);
    }

    public function providesDataForUpdate()
    {
        yield 'updates name' => [
            fn (Collection $printers_ppd, UpdatePromisesTest $test): array => [
                'name' => 'Updated Test Print',
            ],
            function (PrintJobPromise $promise, TestResponse $response, Collection $printers_ppd, UpdatePromisesTest $test): void {
                $test->assertEquals('Updated Test Print', $promise->name);
            },
        ];
        yield 'updates printer' => [
            fn (Collection $printers_ppd, UpdatePromisesTest $test): array => [
                'printer' => $printers_ppd->last()->ulid,
            ],
            function (PrintJobPromise $promise, TestResponse $response, Collection $printers_ppd, UpdatePromisesTest $test): void {
                $test->assertEquals($printers_ppd->last()->ulid, $promise->Printer->ulid);
            },
        ];
        yield 'updates ppd options' => [
            fn (Collection $printers_ppd, UpdatePromisesTest $test): array => [
                'ppd_options' => [
                    'Duplex' => 'True',
                ],
            ],
            function (PrintJobPromise $promise, TestResponse $response, Collection $printers_ppd, UpdatePromisesTest $test): void {
                $test->assertEquals([
                    'Duplex' => 'True',
                ], $promise->ppd_options);
            },
        ];
        yield 'updates meta' => [
            fn (Collection $printers_ppd, UpdatePromisesTest $test): array => [
                'meta' => [
                    'Pages' => '64',
                ],
            ],
            function (PrintJobPromise $promise, TestResponse $response, Collection $printers_ppd, UpdatePromisesTest $test): void {
                $test->assertEquals([
                    'Pages' => '64',
                ], $promise->meta);
            },
        ];
        yield 'updates status' => [
            fn (Collection $printers_ppd, UpdatePromisesTest $test): array => [
                'status' => 'ready',
            ],
            function (PrintJobPromise $promise, TestResponse $response, Collection $printers_ppd, UpdatePromisesTest $test): void {
                $test->assertEquals(PrintJobPromiseStatusEnum::SentToPrinter, $promise->status);
            },
        ];
        yield 'updates status without content' => [
            fn (Collection $printers_ppd, UpdatePromisesTest $test): array => [
                'status' => 'ready',
            ],
            function (PrintJobPromise $promise, TestResponse $response, Collection $printers_ppd, UpdatePromisesTest $test): void {
                $test->assertEquals(PrintJobPromiseStatusEnum::Ready, $promise->status);
            },
            false,
        ];
    }
}
