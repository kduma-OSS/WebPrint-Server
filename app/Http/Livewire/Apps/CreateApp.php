<?php

namespace App\Http\Livewire\Apps;

use App\Actions\Apps\CreateAppAction;
use App\Models\ClientApplication;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateApp extends Component
{
    use AuthorizesRequests;

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

    public function save(CreateAppAction $action)
    {
        $this->authorize('create', [ClientApplication::class, $this->team]);
        $this->validate();

        $app = $action->handle($this->team, $this->name);

        $this->dispatch('saved');

        return redirect()->route('web-print.apps.show', $app);
    }

    public function render()
    {
        return view('livewire.apps.create-app');
    }
}
