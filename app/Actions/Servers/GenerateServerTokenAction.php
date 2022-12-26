<?php

namespace App\Actions\Servers;

use App\Models\PrintServer;

class GenerateServerTokenAction
{
    public function handle(PrintServer $server): string
    {
        $server->tokens->each->delete();

        $token = $server->createToken(sprintf('%s Access Token', $server->name));

        return explode('|', $token->plainTextToken, 2)[1];
    }
}
