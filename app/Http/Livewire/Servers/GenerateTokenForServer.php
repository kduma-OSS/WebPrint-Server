<?php

namespace App\Http\Livewire\Servers;

use App\Actions\Servers\GenerateServerTokenAction;
use App\Actions\Servers\GenerateServerTokenDockerCommandAction;
use App\Actions\Servers\GenerateServerTokenEnvironmentalVariablesAction;
use App\Models\PrintServer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class GenerateTokenForServer extends Component
{
    use AuthorizesRequests;

    public $confirmingApiTokenOverwrite = false;

    public $tokenLastUsed;

    public $displayingToken = false;

    public $plainTextToken;

    public $envVariables;

    public $dockerCommand;

    /**
     * @var PrintServer
     */
    public PrintServer $server;

    public function generateToken(
        GenerateServerTokenAction $action,
        GenerateServerTokenEnvironmentalVariablesAction $envAction,
        GenerateServerTokenDockerCommandAction $dockerAction,
        bool $force = false,
    ) {
        $this->authorize('generateToken', $this->server);

        if ($this->server->tokens->count() > 0 && ! $force) {
            $this->confirmingApiTokenOverwrite = true;
            $this->tokenLastUsed = $this->server->tokens->first()->last_used_at?->diffForHumans();

            return;
        }

        $this->confirmingApiTokenOverwrite = false;

        $this->plainTextToken = $action->handle($this->server);
        $this->envVariables = $envAction->asString($this->plainTextToken);
        $this->dockerCommand = $dockerAction->handle($this->plainTextToken);

        $this->displayingToken = true;

        $this->dispatchBrowserEvent('showing-token-modal');
    }

    public function mount(PrintServer $server)
    {
        $this->server = $server;
    }

    public function render()
    {
        return view('livewire.servers.generate-token-for-server');
    }
}
