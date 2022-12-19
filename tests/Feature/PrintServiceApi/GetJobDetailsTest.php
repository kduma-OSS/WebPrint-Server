<?php

namespace Tests\Feature\PrintServiceApi;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetJobDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token()
    {
        $job = PrintJob::factory()->create();

        $response = $this->getJson('/api/print-service/jobs/'.$job->ulid)
            ->assertUnauthorized();
    }

    public function test_cannot_access_with_different_token()
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'print_service_api'
        );

        $job = PrintJob::factory()->create();

        $this->getJson('/api/print-service/jobs/'.$job->ulid)
            ->assertForbidden();
    }

    public function test_can_access_with_correct_token()
    {
        Sanctum::actingAs(
            $server = PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $printer = Printer::factory()
            ->for($server, 'Server')
            ->create();

        $job = PrintJob::factory()
            ->recycle($printer)
            ->create();

        $this->getJson('/api/print-service/jobs/'.$job->ulid)
            ->assertOk()
            ->assertExactJson([
                'ulid' => $job->ulid,
                'file_name' => $job->file_name,
                'name' => $job->name,
                'options' => [],
                'ppd' => $job->ppd,
                'printer' => [
                    'name' => $job->Printer->name,
                    'uri' => $job->Printer->uri,
                ],
                'size' => $job->size,
                'content' => $job->content,
                'content_type' => 'plain',
                'created_at' => $job->created_at,
            ]);
    }

    public function test_cannot_access_others_jobs()
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $job = PrintJob::factory()->create();

        $this->getJson('/api/print-service/jobs/'.$job->ulid)
            ->assertForbidden();
    }
}
