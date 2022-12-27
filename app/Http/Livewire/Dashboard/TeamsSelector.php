<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class TeamsSelector extends Component
{
    use AuthorizesRequests;

    /**
     * @var Team
     */
    public $team;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Team[]
     */
    public $teams;

    public function mount(User $user): void
    {
        $this->team = $user->currentTeam;
        $this->user = $user;
    }

    public function refresh()
    {
        $this->authorize('viewDashboard', [$this->team, 'teams']);

        $this->teams = $this->user->allTeams()->filter(fn($team) => ! $team->personal_team);
    }

    public function render()
    {
        return view('livewire.dashboard.teams-selector');
    }
}
