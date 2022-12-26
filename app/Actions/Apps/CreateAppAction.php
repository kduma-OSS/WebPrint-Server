<?php

namespace App\Actions\Apps;

use App\Models\ClientApplication;
use App\Models\PrintServer;
use App\Models\Team;

class CreateAppAction
{
    public function handle(Team $team, string $name): ClientApplication
    {
        $app = new ClientApplication();
        $app->name = $name;
        $app->team_id = $team->id;
        $app->save();

        return $app;
    }
}
