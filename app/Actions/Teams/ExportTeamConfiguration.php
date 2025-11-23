<?php

namespace App\Actions\Teams;

use App\Models\Team;

class ExportTeamConfiguration
{
    /**
     * Export team print infrastructure configuration to JSON.
     *
     * @param  \App\Models\Team  $team
     * @return array
     */
    public function export(Team $team): array
    {
        $printServers = $team->PrintServers()
            ->with(['Printers.ClientApplications', 'tokens'])
            ->get()
            ->map(function ($server) {
                return [
                    'ulid' => $server->ulid,
                    'name' => $server->name,
                    'location' => $server->location,
                    'api_token' => $this->getApiToken($server),
                    'printers' => $server->Printers->map(function ($printer) {
                        return [
                            'ulid' => $printer->ulid,
                            'name' => $printer->name,
                            'location' => $printer->location,
                            'enabled' => $printer->enabled,
                            'uri' => $printer->uri,
                            'ppd_support' => $printer->ppd_support,
                            'ppd_options' => $printer->ppd_options,
                            'ppd_options_layout' => $printer->ppd_options_layout,
                            'raw_languages_supported' => $printer->raw_languages_supported,
                        ];
                    })->values()->all(),
                ];
            })->values()->all();

        $clientApplications = $team->ClientApplications()
            ->with(['Printers', 'tokens'])
            ->get()
            ->map(function ($app) {
                return [
                    'ulid' => $app->ulid,
                    'name' => $app->name,
                    'url' => $app->url,
                    'api_token' => $this->getApiToken($app),
                    'printer_access' => $app->Printers->pluck('ulid')->values()->all(),
                ];
            })->values()->all();

        return [
            'version' => '1.0.0',
            'schema_version' => 'webprint-server-1.0',
            'export_date' => now()->toIso8601String(),
            'export_type' => 'team_infrastructure',
            'metadata' => [
                'source_version' => config('app.version', '1.0.0'),
                'exporter_ulid' => auth()->user()?->ulid,
            ],
            'print_servers' => $printServers,
            'client_applications' => $clientApplications,
        ];
    }

    /**
     * Get the hashed API token for a tokenable model.
     *
     * @param  mixed  $tokenable
     * @return string|null
     */
    protected function getApiToken($tokenable): ?string
    {
        $token = $tokenable->tokens->first();

        return $token?->token;
    }

    /**
     * Export team configuration as JSON string.
     *
     * @param  \App\Models\Team  $team
     * @return string
     */
    public function exportAsJson(Team $team): string
    {
        return json_encode($this->export($team), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Generate filename for the export.
     *
     * @param  \App\Models\Team  $team
     * @return string
     */
    public function generateFilename(Team $team): string
    {
        $slug = \Illuminate\Support\Str::slug($team->name);
        $date = now()->format('Y-m-d_His');

        return sprintf('team-config-%s-%s.json', $slug, $date);
    }
}
