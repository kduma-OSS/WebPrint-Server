<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SettingsMigrationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_settings_upgrade_preserves_existing_values_and_lock_state(): void
    {
        $migration = require database_path('migrations/2026_10_21_000000_upgrade_settings_table.php');
        $migration->down();

        DB::table('settings')->where('group', 'general')->where('name', 'site_name')->update([
            'payload' => json_encode('Existing installation'),
            'locked' => true,
        ]);

        $migration->up();

        $this->assertDatabaseHas('settings', [
            'group' => 'general',
            'name' => 'site_name',
            'locked' => true,
        ]);
        $payload = DB::table('settings')->where('group', 'general')->where('name', 'site_name')->value('payload');
        $this->assertSame('Existing installation', json_decode($payload, true));
        $this->assertTrue(Schema::hasIndex('settings', ['group', 'name'], 'unique'));

        DB::table('settings')->insert([
            'group' => 'runtime_test',
            'name' => 'new_setting',
            'payload' => json_encode('new value'),
        ]);

        $this->assertDatabaseHas('settings', [
            'group' => 'runtime_test',
            'name' => 'new_setting',
            'locked' => false,
        ]);
    }
}
