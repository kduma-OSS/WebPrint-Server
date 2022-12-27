<?php

namespace App\Actions\Servers;

class GenerateServerTokenEnvironmentalVariablesAction
{
    public function handle(string $token): array
    {
        return [
            'WEBPRINT_SERVICE_KEY' => $token,
            'WEBPRINT_SERVER_ENDPOINT' => route('api.print-service.index'),
        ];
    }

    public function asString(string $token): string
    {
        return collect($this->handle($token))
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode("\n");
    }
}
