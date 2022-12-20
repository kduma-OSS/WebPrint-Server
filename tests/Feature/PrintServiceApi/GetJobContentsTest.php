<?php

namespace Tests\Feature\PrintServiceApi;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetJobContentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token(): void
    {
        $job = PrintJob::factory()->create();

        $this->getJson('/api/print-service/jobs/'.$job->ulid.'/content')
            ->assertForbidden();
    }

    public function test_cannot_access_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'print_service_api'
        );

        $job = PrintJob::factory()->create();

        $this->getJson('/api/print-service/jobs/'.$job->ulid.'/content')
            ->assertForbidden();
    }

    public function test_cant_access_with_correct_token(): void
    {
        Sanctum::actingAs(
            $server = PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $printer = Printer::factory()
            ->for($server, 'Server')
            ->create();

        $job = PrintJob::factory([
            'content' => 'Hello World',
            'size' => strlen('Hello World'),
        ])
            ->recycle($printer)
            ->create();

        $this->getJson('/api/print-service/jobs/'.$job->ulid.'/content')
            ->assertForbidden();
    }

    public function test_can_access_with_signed_url(): void
    {
        Sanctum::actingAs(
            $server = PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $printer = Printer::factory()
            ->for($server, 'Server')
            ->create();

        $job = PrintJob::factory([
            'content' => 'Hello World',
            'size' => strlen('Hello World'),
        ])
            ->recycle($printer)
            ->create();

        $response = $this
            ->withoutMiddleware(\App\Http\Middleware\ValidateSignature::class)
            ->getJson('/api/print-service/jobs/'.$job->ulid.'/content')
            ->assertOk();

        $this->assertEquals('Hello World', $response->getContent());
    }
}
