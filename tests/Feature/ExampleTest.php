<?php

namespace Tests\Feature;

use App\Models\ClientApplication;
use App\Models\Enums\PrintJobStatusEnum;
use App\Models\Printer;
use App\Models\PrintServer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    private Collection $printers;
    private PrintServer $server;
    private ClientApplication $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = ClientApplication::factory()->create();

        $this->server = PrintServer::factory()
            ->recycle($this->client->Team)
            ->create();

        $this->printers = Printer::factory()
            ->active()
            ->ppd()
            ->for($this->server, 'Server')
            ->count(2)
            ->create();

        $this->client->Printers()->attach($this->printers);
    }

    use RefreshDatabase;

    public function test_simple_one_step_headless_print(): void
    {
        Sanctum::actingAs($this->client, guard: 'web_print_api');

        $create_response = $this
            ->postJson(
                '/api/web-print/promises',
                [
                    'name' => 'Test Print',
                    'type' => 'ppd',
                    'headless' => true,
                    'printer' => $this->printers->first()->ulid,
                    'content' => 'test',
                    'file_name' => 'test.txt',
                ]
            )
            ->assertCreated()
            ->assertJson([
                'data' => [
                    'status' => 'sent_to_printer',
                ]
            ]);

        Sanctum::actingAs($this->server, guard: 'print_service_api');

        $job_ulid = $create_response->json('data.job.ulid');

        $this->getJson('/api/print-service/jobs')
            ->assertOk()
            ->assertExactJson([
                $job_ulid,
            ]);

        $this->getJson('/api/print-service/jobs/'. $job_ulid)
            ->assertOk()
            ->assertJson([
                'content_type' => 'plain',
                'content' => 'test',
                'name' => 'Test Print',
                'size' => '4',
            ]);

        $this->putJson('/api/print-service/jobs/'.$job_ulid, [
            'status' => PrintJobStatusEnum::Failed,
            'status_message' => 'Something went wrong',
        ])->assertNoContent();

        Sanctum::actingAs($this->client, guard: 'web_print_api');

        $this->getJson('/api/web-print/promises/'.$create_response->json('data.ulid'))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'job' => [
                        'status' => 'failed',
                        'status_message' => 'Something went wrong',
                    ],
                ]
            ]);
    }

    public function test_simple_two_steps_headless_print(): void
    {
        Sanctum::actingAs($this->client, guard: 'web_print_api');

        $create_response = $this
            ->postJson(
                '/api/web-print/promises',
                [
                    'name' => 'Test Print',
                    'type' => 'ppd',
                    'headless' => true,
                    'printer' => $this->printers->first()->ulid,
                ]
            )
            ->assertCreated()
            ->assertJson([
                'data' => [
                    'status' => 'ready',
                ]
            ]);

        $promise_ulid = $create_response->json('data.ulid');

        $this->postJson(
            '/api/web-print/promises/'. $promise_ulid .'/content',
            [
                'content' => 'test',
                'name' => 'test.txt',
            ]
        )->assertNoContent();

        $promise_response = $this->getJson('/api/web-print/promises/'. $promise_ulid)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'status' => 'sent_to_printer',
                    'job' => [
                        'status' => 'new',
                    ],
                ]
            ]);

        Sanctum::actingAs($this->server, guard: 'print_service_api');

        $job_ulid = $promise_response->json('data.job.ulid');

        $this->getJson('/api/print-service/jobs')
            ->assertOk()
            ->assertExactJson([
                $job_ulid,
            ]);

        $this->getJson('/api/print-service/jobs/'. $job_ulid)
            ->assertOk()
            ->assertJson([
                'content_type' => 'plain',
                'content' => 'test',
                'name' => 'Test Print',
                'size' => '4',
            ]);

        $this->putJson('/api/print-service/jobs/'.$job_ulid, [
            'status' => PrintJobStatusEnum::Failed,
            'status_message' => 'Something went wrong',
        ])->assertNoContent();

        Sanctum::actingAs($this->client, guard: 'web_print_api');

        $this->getJson('/api/web-print/promises/'. $promise_ulid)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'job' => [
                        'status' => 'failed',
                        'status_message' => 'Something went wrong',
                    ],
                ]
            ]);
    }

    public function test_user_print(): void
    {
        Sanctum::actingAs($this->client, guard: 'web_print_api');

        $create_response = $this
            ->postJson(
                '/api/web-print/promises',
                [
                    'name' => 'Test Print',
                    'type' => 'ppd',
                ]
            )
            ->assertCreated()
            ->assertJson([
                'data' => [
                    'status' => 'new',
                    'content_available' => false,
                ]
            ]);

        $promise_ulid = $create_response->json('data.ulid');

        $this->postJson(
            '/api/web-print/promises/'. $promise_ulid .'/content',
            [
                'content' => 'test',
                'name' => 'test.txt',
            ]
        )->assertNoContent();

        $dialog_response = $this
            ->postJson(
                '/api/web-print/promises/'.$promise_ulid.'/dialog'
            )
            ->assertOk();

        $link = $dialog_response->json('data.link');

        dump($link);
        //TODO: Test browser reaction in dusk
    }
}
