<?php

namespace App\Http\Livewire\Teams;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\ConfirmsPasswords;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class DeleteTeamFormWithConfirmation extends Component
{
    use ConfirmsPasswords;
    use RedirectsActions;

    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * Indicates if team deletion is being confirmed.
     *
     * @var bool
     */
    public $confirmingTeamDeletion = false;

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
     * Delete the team.
     *
     * @return void
     */
    public function deleteTeam(ValidateTeamDeletion $validator, DeletesTeams $deleter)
    {
        $this->ensurePasswordIsConfirmed();

        $validator->validate(Auth::user(), $this->team);

        $deleter->delete($this->team);

        return $this->redirectPath($deleter);
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('teams.delete-team-form');
    }
}
