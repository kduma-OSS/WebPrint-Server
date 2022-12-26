<?php

namespace App\Actions\Apps;

class GenerateAppTokenEnvironmentalVariablesAction
{
    public function handle(string $token): array
    {
        return [
            'WEB_PRINT_ACCESS_TOKEN' => $token,
            'WEB_PRINT_ENDPOINT' => route('api.web-print.index'),
        ];
    }

    public function asString(string $token): string
    {
        return collect($this->handle($token))
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode("\n");
    }
}
