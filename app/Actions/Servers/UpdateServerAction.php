<?php

namespace App\Actions\Servers;

use App\Models\PrintServer;

class UpdateServerAction
{
    public function handle(PrintServer $server, string $name): void
    {
        $server->name = $name;
        $server->save();
    }
}
