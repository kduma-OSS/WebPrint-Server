<?php

namespace App\Actions\Jetstream;

use App\Actions\Apps\DeleteAppAction;
use App\Actions\Servers\DeleteServerAction;
use App\Models\Team;
use Laravel\Jetstream\Contracts\DeletesTeams;

class DeleteTeam implements DeletesTeams
{
    public function __construct(
        protected DeleteServerAction $deleteServerAction,
        protected DeleteAppAction $deleteAppAction,
    ) {
    }

    /**
     * Delete the given team.
     *
     * @param  Team  $team
     */
    public function delete($team): void
    {
        $team->PrintServers->each(fn ($server) => $this->deleteServerAction->handle($server, true));
        $team->ClientApplications->each(fn ($app) => $this->deleteAppAction->handle($app));

        $team->purge();
    }
}
