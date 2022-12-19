<?php

namespace Tests\Feature\PrintServiceApi;

use App\Models\Enums\PrintJobStatusEnum;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListNewJobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token()
    {
        $this->getJson('/api/print-service/jobs')
            ->assertUnauthorized();
    }

    public function test_cannot_access_with_different_token()
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'print_service_api'
        );

        $this->getJson('/api/print-service/jobs')
            ->assertForbidden();
    }

    public function test_can_access_with_correct_token()
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $this->getJson('/api/print-service/jobs')
            ->assertOk()
            ->assertExactJson([]);
    }

    public function test_lists_new_jobs()
    {
        Sanctum::actingAs(
            $server = PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $printer = Printer::factory()
            ->for($server, 'Server')
            ->create();

        $jobs = PrintJob::factory()
            ->recycle($printer)
            ->count(3)
            ->create();

        $this->getJson('/api/print-service/jobs')
            ->assertOk()
            ->assertExactJson(
                $jobs->pluck('ulid')->toArray()
            );
    }

    public function test_cannot_lists_others_jobs()
    {
        Sanctum::actingAs(
            $server = PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $my_printer = Printer::factory()
            ->for($server, 'Server')
            ->create();

        $my_jobs = PrintJob::factory()
            ->recycle($my_printer)
            ->count(3)
            ->create();

        $others_jobs = PrintJob::factory()
            ->count(3)
            ->create();

        $this->getJson('/api/print-service/jobs')
            ->assertOk()
            ->assertExactJson(
                $my_jobs->pluck('ulid')->toArray()
            )->assertJsonMissing(
                $others_jobs->pluck('ulid')->toArray()
            );
    }

    public function test_cannot_lists_non_new_jobs()
    {
        Sanctum::actingAs(
            $server = PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $printer = Printer::factory()
            ->for($server, 'Server')
            ->create();

        $new_jobs = PrintJob::factory()
            ->recycle($printer)
            ->count(3)
            ->create();

        $others_jobs = PrintJob::factory()
            ->sequence(
                ['status' => PrintJobStatusEnum::Printing],
                ['status' => PrintJobStatusEnum::Finished],
                ['status' => PrintJobStatusEnum::Failed],
            )
            ->recycle($printer)
            ->count(3)
            ->create();

        $this->getJson('/api/print-service/jobs')
            ->assertOk()
            ->assertExactJson(
                $new_jobs->pluck('ulid')->toArray()
            )->assertJsonMissing(
                $others_jobs->pluck('ulid')->toArray()
            );
    }
}
