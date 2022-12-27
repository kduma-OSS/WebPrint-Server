<?php

namespace Tests\Feature\Jetstream;

use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintServer;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\DeleteTeamForm;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteTeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_teams_can_be_deleted(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $user->ownedTeams()->save($team = Team::factory()->make([
            'personal_team' => false,
        ]));

        $team->users()->attach(
            $otherUser = User::factory()->create(),
            ['role' => 'test-role']
        );

        Livewire::test(DeleteTeamForm::class, ['team' => $team->fresh()])
            ->call('deleteTeam');

        $this->assertNull($team->fresh());
        $this->assertCount(0, $otherUser->fresh()->teams);
    }

    public function test_teams_with_related_records_can_be_deleted(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $user->ownedTeams()->save($team = Team::factory()->make([
            'personal_team' => false,
        ]));

        $team->users()->attach(
            $otherUser = User::factory()->create(),
            ['role' => 'test-role']
        );

        $client = ClientApplication::factory()
            ->recycle($team)
            ->create();

        $server = PrintServer::factory()
            ->recycle($team)
            ->create();

        $printer = Printer::factory()
            ->active()
            ->for($server, 'Server')
            ->create();

        $client->Printers()->attach($printer);

        Livewire::test(DeleteTeamForm::class, ['team' => $team->fresh()])
            ->call('deleteTeam');

        $this->assertNull($team->fresh());
        $this->assertCount(0, $otherUser->fresh()->teams);
    }

    // public function test_personal_teams_cant_be_deleted(): void
    // {
    //     $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    //
    //     $component = Livewire::test(DeleteTeamForm::class, ['team' => $user->currentTeam])
    //         ->call('deleteTeam')
    //         ->assertHasErrors(['team']);
    //
    //     $this->assertNotNull($user->currentTeam->fresh());
    // }
}
