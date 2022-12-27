<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Enums\PrintJobStatusEnum;
use App\Models\PrintJob;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class FailedJobs extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    /**
     * @var Team
     */
    public $team;

    public $readyToLoad = false;

    public function load()
    {
        $this->readyToLoad = true;
    }

    public function mount(Team $team): void
    {
        $this->team = $team;
    }

    public function render()
    {
        $this->authorize('viewDashboard', [$this->team, 'jobs']);

        return view('livewire.dashboard.jobs', [
            'type' => 'failed',
            'jobs' => $this->readyToLoad ? $this->getData() : null,
        ]);
    }

    protected function getData()
    {
        $printers = $this
            ->team
            ->Printers()
            ->pluck('printers.id');

        return PrintJob::whereIn('printer_id', $printers)
            ->with([
                'Printer',
                'JobPromise',
                'ClientApplication',
            ])
            ->whereIn('status', [
                PrintJobStatusEnum::Failed,
            ])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate();
    }
}
