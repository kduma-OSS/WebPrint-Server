<?php

namespace Tests\Feature\Actions;

use App\Actions\Promises\SetPromiseContentAction;
use App\Models\PrintJobPromise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\TestCase;

class ReplacePromiseContentTest extends TestCase
{
    use RefreshDatabase;

    private PrintJobPromise $promise;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::put('jobs/original.dat', 'Original document');
        $this->promise = PrintJobPromise::factory()->create([
            'content' => null,
            'content_file' => 'jobs/original.dat',
            'file_name' => 'original.dat',
            'size' => 17,
            'print_job_id' => null,
        ])->refresh();
    }

    private function content(string $kind): mixed
    {
        return match ($kind) {
            'inline' => 'New document',
            'file' => str_repeat('New document', 100),
            'upload' => UploadedFile::fake()->createWithContent('new.txt', 'New document'),
            'stream' => $this->stream(),
        };
    }

    private function stream(): mixed
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, 'New document');
        rewind($stream);

        return $stream;
    }

    public static function contentKinds(): array
    {
        return [['inline'], ['file'], ['upload'], ['stream']];
    }

    private function assertOriginalDocumentIsIntact(array $attributes): void
    {
        $this->assertEquals($attributes, $this->promise->fresh()->getAttributes());
        $this->assertSame('Original document', Storage::get('jobs/original.dat'));
        $this->assertSame(['jobs/original.dat'], Storage::allFiles('jobs'));
    }

    #[DataProvider('contentKinds')]
    public function test_database_failure_preserves_the_original_document_and_cleans_up_new_files(string $kind): void
    {
        $attributes = $this->promise->getAttributes();
        Event::listen('eloquent.saved: '.PrintJobPromise::class, function (): void {
            throw new RuntimeException('Simulated database failure');
        });

        $content = $this->content($kind);

        try {
            app(SetPromiseContentAction::class)->handle($this->promise, $content, 'new.txt');
            $this->fail('Expected the database failure to propagate.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated database failure', $exception->getMessage());
        } finally {
            Event::forget('eloquent.saved: '.PrintJobPromise::class);
            if (is_resource($content)) {
                fclose($content);
            }
        }

        $this->assertOriginalDocumentIsIntact($attributes);
    }

    #[DataProvider('contentKinds')]
    public function test_successful_replacement_saves_new_content_before_removing_the_old_file(string $kind): void
    {
        $content = $this->content($kind);
        Event::listen('eloquent.saving: '.PrintJobPromise::class, function (): void {
            Storage::assertExists('jobs/original.dat');
        });

        try {
            app(SetPromiseContentAction::class)->handle($this->promise, $content, 'new.txt');
        } finally {
            Event::forget('eloquent.saving: '.PrintJobPromise::class);
            if (is_resource($content)) {
                fclose($content);
            }
        }

        $this->promise->refresh();
        $expected = $kind === 'file' ? str_repeat('New document', 100) : 'New document';
        $this->assertSame($expected, $this->promise->content ?? Storage::get($this->promise->content_file));
        $this->assertEquals(strlen($expected), $this->promise->size);
        $this->assertSame('new.txt', $this->promise->file_name);
        Storage::assertMissing('jobs/original.dat');
    }

    #[DataProvider('failedWrites')]
    public function test_storage_failure_preserves_the_original_document(string $kind, string $method): void
    {
        $attributes = $this->promise->getAttributes();
        $content = $this->content($kind);
        $disk = Mockery::mock(Storage::disk('local'));
        $disk->shouldReceive($method)->once()->andReturn(false);
        Storage::set('local', $disk);

        try {
            app(SetPromiseContentAction::class)->handle($this->promise, $content, 'new.txt');
            $this->fail('Expected a storage failure.');
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('Unable to store', $exception->getMessage());
        } finally {
            if (is_resource($content)) {
                fclose($content);
            }
        }

        $this->assertOriginalDocumentIsIntact($attributes);
    }

    public static function failedWrites(): array
    {
        return [['file', 'put'], ['upload', 'putFileAs'], ['stream', 'writeStream']];
    }
}
