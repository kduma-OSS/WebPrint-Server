<?php

namespace Tests\Feature;

use App\Actions\Apps\GenerateAppTokenAction;
use App\Actions\Servers\GenerateServerTokenAction;
use App\Http\Livewire\Printers\UpdatePrinter;
use App\Http\Livewire\Settings\GeneralSettingsUpdateForm;
use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Livewire\Livewire;
use Tests\TestCase;

class RuntimeUpgradeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_panel_pages_render_with_livewire_components(): void
    {
        $user = User::factory()->withNonPersonalTeam()->create(['is_system_admin' => true]);
        $server = PrintServer::factory()->recycle($user->currentTeam)->create();
        $printer = Printer::factory()->for($server, 'Server')->create();
        $client = ClientApplication::factory()->recycle($user->currentTeam)->create();
        $client->Printers()->attach($printer);

        $this->actingAs($user);

        foreach ([
            '/dashboard', '/user/profile', '/app/settings',
            '/web-print/servers', '/web-print/servers/create',
            '/web-print/servers/'.$server->ulid,
            '/web-print/servers/'.$server->ulid.'/printers',
            '/web-print/servers/'.$server->ulid.'/printers/create',
            '/web-print/printers/'.$printer->ulid,
            '/web-print/apps', '/web-print/apps/create',
            '/web-print/apps/'.$client->ulid,
            '/web-print/jobs', '/web-print/promises',
            '/teams/'.$user->currentTeam->id,
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_settings_form_saves_and_dispatches_livewire_events(): void
    {
        $this->actingAs(User::factory()->create(['is_system_admin' => true]));

        Livewire::test(GeneralSettingsUpdateForm::class)
            ->set('site_name', 'WebPrint PHP 8.5')
            ->set('language', 'pl')
            ->call('updateGeneralSettings')
            ->assertHasNoErrors()
            ->assertDispatched('saved')
            ->assertDispatched('refresh-navigation-menu');

        $this->assertSame('WebPrint PHP 8.5', app(GeneralSettings::class)->site_name);
        $this->assertSame('pl', app(GeneralSettings::class)->language);
    }

    public function test_user_can_update_a_printer_owned_by_their_team(): void
    {
        $user = User::factory()->withNonPersonalTeam()->create();
        $server = PrintServer::factory()->recycle($user->currentTeam)->create();
        $printer = Printer::factory()->for($server, 'Server')->create(['name' => 'Original printer']);

        Sanctum::actingAs($user, ['*']);

        Livewire::test(UpdatePrinter::class, ['printer' => $printer])
            ->set('name', 'Updated printer')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('saved');

        $this->assertSame('Updated printer', $printer->fresh()->name);
    }

    public function test_user_cannot_mount_another_teams_printer_editor_and_save_changes(): void
    {
        $user = User::factory()->withNonPersonalTeam()->create();
        $printer = Printer::factory()->create(['name' => 'Original printer']);

        Sanctum::actingAs($user, ['*']);

        Livewire::test(UpdatePrinter::class, ['printer' => $printer])
            ->set('name', 'Unauthorized change')
            ->call('save')
            ->assertForbidden();

        $this->assertSame('Original printer', $printer->fresh()->name);
    }

    public function test_real_bearer_tokens_authenticate_only_their_api(): void
    {
        $server = PrintServer::factory()->create();
        $client = ClientApplication::factory()->create();
        $serverToken = app(GenerateServerTokenAction::class)->handle($server);
        $clientToken = app(GenerateAppTokenAction::class)->handle($client);

        foreach ([
            ['/api/print-service/jobs', $serverToken, 200],
            ['/api/web-print/printers', $clientToken, 200],
            ['/api/print-service/jobs', $clientToken, 401],
            ['/api/web-print/printers', $serverToken, 401],
        ] as [$url, $token, $status]) {
            $this->app['auth']->forgetGuards();
            $this->getJson($url, ['Authorization' => 'Bearer '.$token])->assertStatus($status);
        }
    }

    public function test_cleanup_removes_expired_files_and_records_but_preserves_recent_jobs(): void
    {
        Storage::fake('local');
        $this->freezeTime();

        $recent = PrintJobPromise::factory()->withContent()->create();
        $expired = PrintJobPromise::factory()->create([
            'status' => 'new',
            'content_file' => 'jobs/expired.dat',
            'updated_at' => now()->subHours(25),
        ]);
        $staleJob = PrintJob::factory()->create([
            'status' => 'finished',
            'content_file' => 'jobs/stale.dat',
            'updated_at' => now()->subDays(2),
        ]);
        Storage::put('jobs/expired.dat', 'expired content');
        Storage::put('jobs/stale.dat', 'stale content');

        $this->artisan('print_jobs:cleanup')->assertSuccessful();

        $this->assertModelMissing($expired);
        $this->assertModelExists($recent);
        $this->assertNotNull($recent->fresh()->content);
        $this->assertNull($staleJob->fresh()->content_file);
        Storage::assertMissing(['jobs/expired.dat', 'jobs/stale.dat']);
    }
}
