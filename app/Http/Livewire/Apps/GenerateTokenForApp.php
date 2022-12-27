<?php

namespace App\Http\Livewire\Apps;

use App\Actions\Apps\GenerateAppTokenAction;
use App\Actions\Apps\GenerateAppTokenEnvironmentalVariablesAction;
use App\Models\ClientApplication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class GenerateTokenForApp extends Component
{
    use AuthorizesRequests;

    public $confirmingApiTokenOverwrite = false;

    public $tokenLastUsed;

    public $displayingToken = false;

    public $plainTextToken;

    public $envVariables;

    /**
     * @var ClientApplication
     */
    public ClientApplication $app;

    public function generateToken(
        GenerateAppTokenAction $action,
        GenerateAppTokenEnvironmentalVariablesAction $envAction,
        bool $force = false,
    ) {
        $this->authorize('generateToken', $this->app);

        if ($this->app->tokens->count() > 0 && ! $force) {
            $this->confirmingApiTokenOverwrite = true;
            $this->tokenLastUsed = $this->app->tokens->first()->last_used_at?->diffForHumans();

            return;
        }

        $this->confirmingApiTokenOverwrite = false;

        $this->plainTextToken = $action->handle($this->app);
        $this->envVariables = $envAction->asString($this->plainTextToken);

        $this->displayingToken = true;

        $this->dispatchBrowserEvent('showing-token-modal');
    }

    public function mount(ClientApplication $app)
    {
        $this->app = $app;
    }

    public function render()
    {
        return view('livewire.apps.generate-token-for-app');
    }
}
