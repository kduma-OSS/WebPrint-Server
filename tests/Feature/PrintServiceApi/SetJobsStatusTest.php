<?php

namespace Tests\Feature\PrintServiceApi;

use App\Models\Enums\PrintJobStatusEnum;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SetJobsStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_without_token(): void
    {
        $job = PrintJob::factory()->create();

        $this->putJson('/api/print-service/jobs/'.$job->ulid, [
            'status' => PrintJobStatusEnum::Printing,
        ])
            ->assertUnauthorized();
    }

    public function test_cannot_access_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'print_service_api'
        );

        $job = PrintJob::factory()->create();

        $this->putJson('/api/print-service/jobs/'.$job->ulid, [
            'status' => PrintJobStatusEnum::Printing,
        ])
            ->assertForbidden();
    }

    public function test_can_update_to_printing_with_correct_token(): void
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

        $this->putJson('/api/print-service/jobs/'.$job->ulid, [
            'status' => PrintJobStatusEnum::Printing,
        ])->assertNoContent();

        $this->assertEquals(PrintJobStatusEnum::Printing, $job->fresh()->status);
    }

    public function test_can_update_to_finished_with_correct_token(): void
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

        $this->putJson('/api/print-service/jobs/'.$job->ulid, [
            'status' => PrintJobStatusEnum::Finished,
        ])->assertNoContent();

        $this->assertEquals(PrintJobStatusEnum::Finished, $job->fresh()->status);
    }

    public function test_can_update_to_failed_with_correct_token(): void
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

        $this->putJson('/api/print-service/jobs/'.$job->ulid, [
            'status' => PrintJobStatusEnum::Failed,
            'status_message' => 'Something went wrong',
        ])->assertNoContent();

        $this->assertEquals(PrintJobStatusEnum::Failed, $job->fresh()->status);
        $this->assertEquals('Something went wrong', $job->fresh()->status_message);
    }

    public function test_cannot_be_updated_to_failed_without_message(): void
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

        $this->putJson('/api/print-service/jobs/'.$job->ulid, [
            'status' => PrintJobStatusEnum::Failed,
        ])->assertJsonValidationErrorFor('status_message');
    }

    public function test_cannot_update_others_jobs(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'print_service_api'
        );

        $job = PrintJob::factory()->create();

        $this->putJson('/api/print-service/jobs/'.$job->ulid, [
            'status' => PrintJobStatusEnum::Printing,
        ])->assertForbidden();

        $this->assertEquals(PrintJobStatusEnum::New, $job->fresh()->status);
    }
}
