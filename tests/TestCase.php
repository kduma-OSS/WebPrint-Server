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

    protected function skipIfUsingInSQLiteDatabase()
    {
        $default = config('database.default');

        if (config(sprintf('database.connections.%s.driver', $default)) === 'sqlite') {
            $this->markTestSkipped('Not compatible with SQLite database.');
        }
    }
}
