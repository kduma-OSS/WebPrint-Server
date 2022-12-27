<?php

namespace App\Http\Livewire\Servers;

use App\Actions\Servers\UpdateServerAction;
use App\Models\PrintServer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class UpdateServer extends Component
{
    use AuthorizesRequests;

    /**
     * @var PrintServer
     */
    public PrintServer $server;

    /**
     * @var string
     */
    public $name;

    /**
     * @var ?string
     */
    public $location;

    protected $rules = [
        'name' => ['required', 'string', 'min:1', 'max:255'],
        'location' => ['nullable', 'string', 'min:1', 'max:255'],
    ];

    public function mount(PrintServer $server)
    {
        $this->server = $server;
        $this->name = $server->name;
        $this->location = $server->location;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save(UpdateServerAction $action)
    {
        $this->authorize('update', $this->server);
        $this->validate();

        $action->handle($this->server, $this->name, $this->location);

        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.servers.update-server');
    }
}
