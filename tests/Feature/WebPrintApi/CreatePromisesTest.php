<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\PrintDialog;
use App\Models\Printer;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatePromisesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_create_without_token(): void
    {
        $this
            ->postJson('/api/web-print/promises', [
                'name' => 'Test Print',
                'type' => 'ppd',
            ])
            ->assertUnauthorized();
    }

    public function test_cannot_create_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $this
            ->postJson('/api/web-print/promises', [
                'name' => 'Test Print',
                'type' => 'ppd',
            ])
            ->assertForbidden();
    }

    public function test_cannot_create_others_dialog(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $this
            ->postJson('/api/web-print/promises', [
                'name' => 'Test Print',
                'type' => 'ppd',
            ])
            ->assertForbidden();
    }

    /**
     * @dataProvider providesDataForCreate
     */
    public function test_can_create_with_correct_token(callable $post, callable $expected, callable $additional = null): void
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
            ->sequence(
                ['raw_languages_supported' => ['pcl']],
                ['raw_languages_supported' => ['escpos']],
            )
            ->noPpd()
            ->for($server, 'Server')
            ->count(2)
            ->create();

        $client->Printers()->attach($printers);

        $printers_ppd = Printer::factory()
            ->active()
            ->ppd()
            ->for($server, 'Server')
            ->count(2)
            ->create();

        $client->Printers()->attach($printers_ppd);

        if ($additional) {
            $additional($this);
        }

        $response = $this
            ->postJson(
                '/api/web-print/promises',
                $post($printers, $printers_ppd, $this)
            )
            ->assertCreated();

        $promise = PrintJobPromise::where('ulid', $response->json('data.ulid'))->firstOrFail();

        $response->assertExactJson([
            'data' => $expected($promise, $response, $printers, $printers_ppd, $this),
        ]);
    }

    public function providesDataForCreate()
    {
        yield 'minimal' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'ppd',
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'ppd',
                'name' => 'Test Print',
                'available_printers' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->toArray(),

                'content_available' => false,
                'job' => null,
                'file_name' => null,
                'meta' => null,
                'ppd_options' => null,
                'selected_printer' => null,
                'size' => null,
                'status' => 'new',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
        ];

        yield 'with content' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'ppd',
                'content' => 'test',
                'file_name' => 'test.txt',
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'ppd',
                'name' => 'Test Print',
                'available_printers' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->toArray(),

                'content_available' => true,
                'file_name' => 'test.txt',
                'size' => 4,

                'job' => null,
                'meta' => null,
                'ppd_options' => null,
                'selected_printer' => null,
                'status' => 'new',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
        ];

        yield 'raw' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'escpos',
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'escpos',
                'name' => 'Test Print',
                'available_printers' => $printers->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->skip(1)->values()->toArray(),
                'selected_printer' => $printers->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->last(),

                'content_available' => false,
                'job' => null,
                'file_name' => null,
                'meta' => null,
                'ppd_options' => null,
                'size' => null,
                'status' => 'new',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
            fn (CreatePromisesTest $test) =>
                $test->skipIfUsingInSQLiteDatabase(),
        ];

        yield 'meta' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'ppd',
                'meta' => [
                    'Pages' => '64',
                ],
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'ppd',
                'name' => 'Test Print',
                'available_printers' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->toArray(),
                'meta' => [
                    'Pages' => '64',
                ],

                'content_available' => false,
                'job' => null,
                'file_name' => null,
                'ppd_options' => null,
                'selected_printer' => null,
                'size' => null,
                'status' => 'new',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
        ];

        yield 'ppd options' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'ppd',
                'ppd_options' => [
                    'Duplex' => 'true',
                ],
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'ppd',
                'name' => 'Test Print',
                'available_printers' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->toArray(),
                'ppd_options' => [
                    'Duplex' => 'true',
                ],

                'meta' => null,
                'content_available' => false,
                'job' => null,
                'file_name' => null,
                'selected_printer' => null,
                'size' => null,
                'status' => 'new',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
        ];

        yield 'selected printer' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'ppd',
                'printer' => $printers_ppd->first()->ulid,
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'ppd',
                'name' => 'Test Print',
                'available_printers' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->toArray(),
                'selected_printer' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->first(),

                'content_available' => false,
                'job' => null,
                'file_name' => null,
                'meta' => null,
                'ppd_options' => null,
                'size' => null,
                'status' => 'new',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
        ];

        yield 'limited printers' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'ppd',
                'available_printers' => $printers_ppd->take(1)->pluck('ulid')->toArray(),
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'ppd',
                'name' => 'Test Print',
                'available_printers' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->take(1)->toArray(),
                'selected_printer' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->first(),

                'content_available' => false,
                'job' => null,
                'file_name' => null,
                'meta' => null,
                'ppd_options' => null,
                'size' => null,
                'status' => 'new',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
        ];

        yield 'headless without content' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'ppd',
                'headless' => true,
                'printer' => $printers_ppd->first()->ulid,
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'ppd',
                'name' => 'Test Print',
                'available_printers' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->toArray(),
                'selected_printer' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->first(),

                'content_available' => false,
                'job' => null,
                'file_name' => null,
                'meta' => null,
                'ppd_options' => null,
                'size' => null,
                'status' => 'ready',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
        ];

        yield 'headless with content' => [
            fn (Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'name' => 'Test Print',
                'type' => 'ppd',
                'headless' => true,
                'printer' => $printers_ppd->first()->ulid,
                'content' => 'test',
                'file_name' => 'test.txt',
            ],
            fn (PrintJobPromise $promise, TestResponse $response, Collection $printers, Collection $printers_ppd, CreatePromisesTest $test) => [
                'type' => 'ppd',
                'name' => 'Test Print',
                'available_printers' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->toArray(),
                'selected_printer' => $printers_ppd->map(fn (Printer $printer) => [
                    'location' => $printer->location,
                    'name' => $printer->name,
                    'ppd_support' => $printer->ppd_support,
                    'raw_languages_supported' => $printer->raw_languages_supported,
                    'ulid' => $printer->ulid,
                ])->first(),

                'content_available' => true,
                'file_name' => 'test.txt',
                'size' => 4,

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

                'meta' => null,
                'ppd_options' => null,
                'status' => 'sent_to_printer',

                'ulid' => $promise->ulid,
                'created_at' => $promise->created_at,
                'updated_at' => $promise->updated_at,
            ],
        ];
    }
}
