<?php

namespace App\Http\Livewire\Servers;

use App\Actions\Servers\CreateServerAction;
use App\Models\PrintServer;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateServer extends Component
{
    use AuthorizesRequests;

    /**
     * @var Team
     */
    public Team $team;

    /**
     * @var string
     */
    public $name;

    protected $rules = [
        'name' => ['required', 'string', 'min:1', 'max:255'],
    ];

    public function mount()
    {
        $this->team = auth()->user()->currentTeam;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save(CreateServerAction $action)
    {
        $this->authorize('create', [PrintServer::class, $this->team]);
        $this->validate();

        $server = $action->handle($this->team, $this->name);

        $this->emit('saved');

        return redirect()->route('web-print.servers.show', $server);
    }

    public function render()
    {
        return view('livewire.servers.create-server');
    }
}
