<?php

namespace App\Http\Livewire\Apps;

use App\Actions\Apps\UpdateAppAction;
use App\Actions\Servers\UpdateServerAction;
use App\Models\ClientApplication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class UpdateApp extends Component
{
    use AuthorizesRequests;

    /**
     * @var ClientApplication
     */
    public ClientApplication $app;

    /**
     * @var string
     */
    public $name;

    protected $rules = [
        'name' => ['required', 'string', 'min:1', 'max:255'],
    ];

    public function mount(ClientApplication $app)
    {
        $this->app = $app;
        $this->name = $app->name;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save(UpdateAppAction $action)
    {
        $this->authorize('update', $this->app);
        $this->validate();

        $action->handle($this->app, $this->name);

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
    }

    public function render()
    {
        return view('livewire.apps.update-app');
    }
}
