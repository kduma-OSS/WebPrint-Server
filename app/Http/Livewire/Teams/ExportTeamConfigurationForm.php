<?php

namespace App\Http\Livewire\Teams;

use App\Actions\Teams\ExportTeamConfiguration;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\ConfirmsPasswords;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportTeamConfigurationForm extends Component
{
    use ConfirmsPasswords;

    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * Indicates if export is being confirmed.
     *
     * @var bool
     */
    public $confirmingExport = false;

    /**
     * Mount the component.
     *
     * @param  mixed  $team
     * @return void
     */
    public function mount($team)
    {
        $this->team = $team;
    }

    /**
     * Confirm export and check password.
     *
     * @return void
     */
    public function confirmExport()
    {
        $this->ensurePasswordIsConfirmed();

        $this->confirmingExport = true;
    }

    /**
     * Export the team configuration.
     *
     * @param  \App\Actions\Teams\ExportTeamConfiguration  $exporter
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(ExportTeamConfiguration $exporter): StreamedResponse
    {
        Gate::authorize('export', $this->team);

        $json = $exporter->exportAsJson($this->team);
        $filename = $exporter->generateFilename($this->team);

        $this->confirmingExport = false;

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('teams.export-team-configuration-form');
    }
}
