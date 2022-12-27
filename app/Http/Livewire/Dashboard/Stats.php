<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Enums\PrintJobStatusEnum;
use App\Models\PrintJob;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Stats extends Component
{
    use AuthorizesRequests;

    /**
     * @var Team
     */
    public $team;

    public $pending_jobs;

    public $failed_jobs;

    public $finished_jobs;

    public function mount(Team $team): void
    {
        $this->team = $team;
    }

    public function refresh()
    {
        $this->authorize('view', $this->team);

        $printers = $this
            ->team
            ->Printers()
            ->pluck('printers.id');

        $this->pending_jobs = PrintJob::query()
            ->whereIn('printer_id', $printers)
            ->whereIn('status', [
                PrintJobStatusEnum::New,
                PrintJobStatusEnum::Printing,
            ])
            ->count();

        $this->failed_jobs = PrintJob::query()
            ->whereIn('printer_id', $printers)
            ->whereIn('status', [
                PrintJobStatusEnum::Failed,
            ])
            ->count();

        $this->finished_jobs = PrintJob::query()
            ->whereIn('printer_id', $printers)
            ->whereIn('status', [
                PrintJobStatusEnum::Finished,
            ])
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard.stats');
    }
}
