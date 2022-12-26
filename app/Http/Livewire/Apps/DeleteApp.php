<?php

namespace App\Http\Livewire\Apps;

use App\Actions\Apps\DeleteAppAction;
use App\Actions\Servers\DeleteServerAction;
use App\Actions\Servers\ServerCanBeDeletedAction;
use App\Models\ClientApplication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class DeleteApp extends Component
{
    use AuthorizesRequests;

    /**
     * @var ClientApplication
     */
    public ClientApplication $app;

    public $confirmingAppDeletion = false;

    public function getCanBeDeletedProperty(): bool
    {
        return true;
    }

    public function deleteApp(DeleteAppAction $action)
    {
        $this->authorize('update', $this->app);

        $action->handle($this->app);

        return redirect()->route('web-print.apps.index');
    }

    public function mount(ClientApplication $app)
    {
        $this->app = $app;
    }

    public function render()
    {
        return view('livewire.apps.delete-app');
    }
}
