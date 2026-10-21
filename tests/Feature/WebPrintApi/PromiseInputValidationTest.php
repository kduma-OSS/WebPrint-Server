<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PromiseInputValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(ClientApplication::factory()->create(), guard: 'web_print_api');
    }

    #[DataProvider('invalidFields')]
    public function test_invalid_fields_are_rejected_before_creating_a_promise(string $field, mixed $value): void
    {
        $this->postJson('/api/web-print/promises', array_replace([
            'name' => 'Test job',
            'type' => 'ppd',
            'file_name' => 'test.txt',
            'content' => 'Test document',
        ], [$field => $value]))->assertUnprocessable()->assertJsonValidationErrors($field);

        $this->assertDatabaseCount('print_job_promises', 0);
        $this->assertDatabaseCount('print_jobs', 0);
    }

    public static function invalidFields(): \Generator
    {
        foreach (['type', 'file_name'] as $field) {
            yield $field.' ASCII' => [$field, str_repeat('a', 256)];
            yield $field.' multibyte' => [$field, str_repeat('ą', 256)];
            yield $field.' array' => [$field, ['invalid']];
        }
        yield 'array content' => ['content', ['invalid']];
        yield 'object content' => ['content', ['document' => 'invalid']];
    }

    #[DataProvider('boundaryFields')]
    public function test_fields_accept_255_characters(string $field, string $value): void
    {
        $this->postJson('/api/web-print/promises', array_replace([
            'name' => 'Test job',
            'type' => 'ppd',
            'file_name' => 'test.txt',
            'content' => 'Test document',
        ], [$field => $value]))->assertCreated()->assertJsonPath('data.'.$field, $value);

        $this->assertDatabaseHas('print_job_promises', [$field => $value]);
    }

    public static function boundaryFields(): \Generator
    {
        foreach (['type', 'file_name'] as $field) {
            yield $field.' ASCII' => [$field, str_repeat('a', 255)];
            yield $field.' multibyte' => [$field, str_repeat('ą', 255)];
        }
    }
}
