<?php

namespace App\Actions\Servers;

use App\Models\PrintServer;
use App\Models\Team;

class CreateServerAction
{
    public function handle(Team $team, string $name): PrintServer
    {
        $server = new PrintServer();
        $server->name = $name;
        $server->team_id = $team->id;
        $server->save();

        return $server;
    }
}
