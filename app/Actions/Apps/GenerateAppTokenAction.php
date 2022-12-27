<?php

namespace App\Actions\Apps;

use App\Models\ClientApplication;

class GenerateAppTokenAction
{
    public function handle(ClientApplication $app): string
    {
        $app->tokens->each->delete();

        $token = $app->createToken(sprintf('%s Access Token', $app->name));

        return explode('|', $token->plainTextToken, 2)[1];
    }
}
