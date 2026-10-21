<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PrintJobNameValidationTest extends TestCase
{
    use RefreshDatabase;

    private ClientApplication $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = ClientApplication::factory()->create();
        Sanctum::actingAs($this->client, guard: 'web_print_api');
    }

    #[DataProvider('nameCharacters')]
    public function test_create_rejects_a_name_longer_than_255_characters(string $character): void
    {
        $this->postJson('/api/web-print/promises', [
            'name' => str_repeat($character, 256),
            'type' => 'ppd',
        ])->assertUnprocessable()->assertJsonValidationErrors('name');

        $this->assertDatabaseCount('print_job_promises', 0);
        $this->assertDatabaseCount('print_jobs', 0);
    }

    #[DataProvider('nameCharacters')]
    public function test_update_rejects_a_long_name_without_changing_the_promise(string $character): void
    {
        $promise = PrintJobPromise::factory()->for($this->client)->withoutContent()->create([
            'name' => 'Original name',
            'print_job_id' => null,
            'printer_id' => null,
        ]);

        $this->putJson('/api/web-print/promises/'.$promise->ulid, [
            'name' => str_repeat($character, 256),
        ])->assertUnprocessable()->assertJsonValidationErrors('name');

        $this->assertSame('Original name', $promise->fresh()->name);
        $this->assertDatabaseCount('print_jobs', 0);
    }

    #[DataProvider('nameCharacters')]
    public function test_create_preserves_a_255_character_name_in_the_print_job(string $character): void
    {
        $name = str_repeat($character, 255);
        $server = PrintServer::factory()->recycle($this->client->Team)->create();
        $printer = Printer::factory()->active()->ppd()->for($server, 'Server')->create();
        $this->client->Printers()->attach($printer);

        $this->postJson('/api/web-print/promises', [
            'name' => $name,
            'type' => 'ppd',
            'headless' => true,
            'printer' => $printer->ulid,
            'content' => 'Test document',
            'file_name' => 'test.txt',
        ])->assertCreated()
            ->assertJsonPath('data.name', $name)
            ->assertJsonPath('data.job.name', $name);

        $this->assertDatabaseHas('print_jobs', ['name' => $name]);
    }

    #[DataProvider('nameCharacters')]
    public function test_update_accepts_a_255_character_name(string $character): void
    {
        $name = str_repeat($character, 255);
        $promise = PrintJobPromise::factory()->for($this->client)->withoutContent()->create([
            'print_job_id' => null,
            'printer_id' => null,
        ]);

        $this->putJson('/api/web-print/promises/'.$promise->ulid, [
            'name' => $name,
        ])->assertNoContent();

        $this->assertSame($name, $promise->fresh()->name);
    }

    public static function nameCharacters(): array
    {
        return [
            'ASCII' => ['a'],
            'multibyte' => ['ą'],
        ];
    }
}
