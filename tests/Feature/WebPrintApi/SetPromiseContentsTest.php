<?php

namespace Tests\Feature\WebPrintApi;

use App\Models\ClientApplication;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SetPromiseContentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_set_without_token(): void
    {
        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $this->assertEquals(null, $promise->content);

        $this
            ->postJson('/api/web-print/promises/'.$promise->ulid.'/content', [
                'content' => 'test',
            ])
            ->assertUnauthorized();

        $this->assertEquals(null, $promise->fresh()->content);
    }

    public function test_cannot_set_with_different_token(): void
    {
        Sanctum::actingAs(
            User::factory()->withNonPersonalTeam()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $this->assertEquals(null, $promise->content);

        $this
            ->postJson('/api/web-print/promises/'.$promise->ulid.'/content', [
                'content' => 'test',
            ])
            ->assertForbidden();

        $this->assertEquals(null, $promise->fresh()->content);
    }

    public function test_cannot_set_others_content(): void
    {
        Sanctum::actingAs(
            PrintServer::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->create();

        $this->assertEquals(null, $promise->content);

        $this
            ->postJson('/api/web-print/promises/'.$promise->ulid.'/content', [
                'content' => 'test',
            ])
            ->assertForbidden();

        $this->assertEquals(null, $promise->fresh()->content);
    }

    public function test_can_set_with_correct_token_as_json(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->for($client)
            ->create();

        $this->assertEquals(null, $promise->content);
        $this->assertEquals(null, $promise->file_name);

        $response = $this->postJson(
            '/api/web-print/promises/'.$promise->ulid.'/content',
            [
                'content' => 'test',
                'name' => 'test.txt',
            ]
        )->assertNoContent();

        $this->assertEquals($promise->content, $response->getContent());

        $this->assertEquals('test', $promise->fresh()->content);
        $this->assertEquals(4, $promise->fresh()->size);
        $this->assertEquals('test.txt', $promise->fresh()->file_name);
    }

    public function test_can_set_with_correct_token_as_post(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->for($client)
            ->create();

        $this->assertEquals(null, $promise->content);
        $this->assertEquals(null, $promise->file_name);

        $file = UploadedFile::fake()->create('test.txt', 'test');
        Storage::fake();

        $response = $this->postJson(
            '/api/web-print/promises/'.$promise->ulid.'/content',
            [
                'content' => $file,
            ]
        )->assertNoContent();

//        $response = $this->call(
//            'post',
//            '/api/web-print/promises/'.$promise->ulid.'/content',
//            parameters: [
//                'content' => 'test',
//                'name' => 'test.txt',
//            ],
//            files: [
//                'content' => 'test',
//            ]
//        )->assertNoContent();

        $this->assertEquals($promise->content, $response->getContent());

        $this->assertEquals('test.txt', $promise->fresh()->file_name);
        $this->assertEquals(null, $promise->fresh()->content);
        $this->assertNotEquals(null, $promise->fresh()->content_file);
        $this->assertEquals(4, $promise->fresh()->size);
        Storage::assertExists($promise->fresh()->content_file);
    }

    public function test_can_set_with_correct_token_as_content(): void
    {
        Sanctum::actingAs(
            $client = ClientApplication::factory()->create(),
            guard: 'web_print_api'
        );

        $promise = PrintJobPromise::factory()
            ->withoutContent()
            ->for($client)
            ->create();

        $this->assertEquals(null, $promise->content);
        $this->assertEquals(null, $promise->file_name);

        UploadedFile::fake()->create('test.txt', 'test');
        Storage::fake();

        $response = $this->call(
            'post',
            '/api/web-print/promises/'.$promise->ulid.'/content',
            server: [
                'HTTP_X-FILE-NAME' => 'test.txt',
            ],
            content:'test'
        )->assertNoContent();

        $this->assertEquals($promise->content, $response->getContent());

        $this->assertEquals('test.txt', $promise->fresh()->file_name);
        $this->assertEquals(null, $promise->fresh()->content);
        $this->assertEquals(4, $promise->fresh()->size);
        $this->assertNotEquals(null, $promise->fresh()->content_file);

        Storage::assertExists($promise->fresh()->content_file);
    }
}
