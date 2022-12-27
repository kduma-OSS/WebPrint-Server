<?php

namespace App\Http\Livewire\Servers;

use App\Actions\Servers\DeleteServerAction;
use App\Actions\Servers\ServerCanBeDeletedAction;
use App\Models\PrintServer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class DeleteServer extends Component
{
    use AuthorizesRequests;

    /**
     * @var PrintServer
     */
    public PrintServer $server;

    public $confirmingServerDeletion = false;

    public function getCanBeDeletedProperty(ServerCanBeDeletedAction $action): bool
    {
        return $action->handle($this->server);
    }

    public function deleteServer(DeleteServerAction $action)
    {
        $this->authorize('update', $this->server);

        $action->handle($this->server);

        return redirect()->route('web-print.servers.index');
    }

    public function mount(PrintServer $server)
    {
        $this->server = $server;
    }

    public function render()
    {
        return view('livewire.servers.delete-server');
    }
}
