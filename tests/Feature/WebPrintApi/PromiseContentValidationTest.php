<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\PrintJobPromise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PromiseContentValidationTest extends TestCase
{
    use RefreshDatabase;

    private ClientApplication $client;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->client = ClientApplication::factory()->create();
        Sanctum::actingAs($this->client, guard: 'web_print_api');
    }

    private function promise(array $attributes = []): PrintJobPromise
    {
        return PrintJobPromise::factory()->for($this->client)->withoutContent()->create(array_replace([
            'print_job_id' => null,
            'printer_id' => null,
        ], $attributes))->refresh();
    }

    private function sendContent(PrintJobPromise $promise, string $transport, mixed $name, mixed $content = 'New document'): TestResponse
    {
        $url = '/api/web-print/promises/'.$promise->ulid.'/content';

        return match ($transport) {
            'json' => $this->postJson($url, ['name' => $name, 'content' => $content]),
            'multipart' => $this->call('POST', $url, files: [
                'content' => UploadedFile::fake()->createWithContent($name, $content),
            ], server: ['HTTP_ACCEPT' => 'application/json']),
            'header' => $this->call('POST', $url, server: [
                'HTTP_X_FILE_NAME' => $name,
                'HTTP_ACCEPT' => 'application/json',
            ], content: $content),
        };
    }

    #[DataProvider('invalidPayloads')]
    public function test_invalid_content_preserves_the_previous_document(string $transport, mixed $name, mixed $content, string $error): void
    {
        Storage::put('jobs/original.dat', 'Original document');
        $promise = $this->promise([
            'file_name' => 'original.dat',
            'content_file' => 'jobs/original.dat',
            'size' => 17,
        ]);
        $original = $promise->getAttributes();

        $this->sendContent($promise, $transport, $name, $content)
            ->assertUnprocessable()->assertJsonValidationErrors($error);

        $this->assertEquals($original, $promise->fresh()->getAttributes());
        $this->assertSame('Original document', Storage::get('jobs/original.dat'));
        $this->assertSame(['jobs/original.dat'], Storage::allFiles('jobs'));
    }

    public static function invalidPayloads(): \Generator
    {
        foreach (['json', 'multipart', 'header'] as $transport) {
            foreach (['a', 'ą'] as $character) {
                yield $transport.' '.$character => [$transport, str_repeat($character, 256), 'New document', 'name'];
            }
        }
        yield 'array name' => ['json', ['invalid'], 'New document', 'name'];
        yield 'array content' => ['json', 'valid.txt', ['invalid'], 'content'];
    }

    #[DataProvider('boundaryNames')]
    public function test_content_accepts_a_255_character_filename(string $transport, string $name): void
    {
        $promise = $this->promise();

        $this->sendContent($promise, $transport, $name)->assertNoContent();

        $promise->refresh();
        $this->assertSame($name, $promise->file_name);
        $this->assertSame('New document', $promise->content ?? Storage::get($promise->content_file));
    }

    public static function boundaryNames(): \Generator
    {
        foreach (['json', 'multipart', 'header'] as $transport) {
            yield $transport.' ASCII' => [$transport, str_repeat('a', 255)];
            yield $transport.' multibyte' => [$transport, str_repeat('ą', 255)];
        }
    }
}
