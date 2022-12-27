<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\PrintServer;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class PrintServers extends Component
{
    use AuthorizesRequests;

    /**
     * @var Team
     */
    public $team;

    /**
     * @var PrintServer[]
     */
    public $servers;

    public function refresh()
    {
        $this->authorize('viewDashboard', [$this->team, 'stats']);

        $this->servers = $this
            ->team
            ->PrintServers()
            ->orderByRaw('ISNULL(last_active_at) DESC, last_active_at ASC')
            ->limit(6)
            ->get();
    }

    public function mount(Team $team): void
    {
        $this->team = $team;
    }

    public function render()
    {
        return view('livewire.dashboard.print-servers');
    }
}
