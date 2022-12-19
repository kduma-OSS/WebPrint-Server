<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        Http::preventStrayRequests();
    }

    protected function skipIfUsingInMemoryDatabase()
    {
        $default = config('database.default');

        if (config("database.connections.$default.driver") === 'sqlite') {
            $this->markTestSkipped('Not compatible with SQLite database.');
        }
    }
}
