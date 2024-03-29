<?php

namespace App\Http\Livewire\Apps;

use App\Actions\Apps\UpdateAppAction;
use App\Actions\Apps\UpdateAppPrintersAction;
use App\Models\ClientApplication;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
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

    /**
     * @var ?string
     */
    public $url;

    /**
     * @var string[]
     */
    public $printers;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'url' => ['nullable', 'url', 'min:1', 'max:255'],
            'printers' => ['array'],
            'printers.*' => ['required', 'string', Rule::in($this->app->Team->Printers()->pluck('printers.ulid')->toArray())],
        ];
    }

    public function mount(ClientApplication $app)
    {
        $this->app = $app;
        $this->name = $app->name;
        $this->url = $app->url;
        $this->printers = $app->Printers()->pluck('ulid')->toArray();
    }

    public function getAvailablePrintersProperty(): Collection
    {
        return $this->app->Team->Printers()
            ->with('Server')
            ->orderBy('name')
            ->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save(UpdateAppAction $action, UpdateAppPrintersAction $printersAction)
    {
        $this->authorize('update', $this->app);
        $this->validate();

        $action->handle($this->app, $this->name, $this->url);
        $printersAction->handle($this->app, $this->printers);

        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.apps.update-app');
    }
}
