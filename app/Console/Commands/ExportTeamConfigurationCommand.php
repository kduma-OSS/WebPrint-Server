<?php

namespace App\Console\Commands;

use App\Actions\Teams\ExportTeamConfiguration;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportTeamConfigurationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team:export-configuration
                            {team_ulid : The ULID of the team to export}
                            {--output= : Output file path (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export team print infrastructure configuration to JSON';

    /**
     * Execute the console command.
     *
     * @param  \App\Actions\Teams\ExportTeamConfiguration  $exporter
     * @return int
     */
    public function handle(ExportTeamConfiguration $exporter): int
    {
        $teamUlid = $this->argument('team_ulid');

        // Find team by ULID
        $team = Team::where('ulid', $teamUlid)->first();

        if (! $team) {
            $this->error("Team with ULID '{$teamUlid}' not found.");

            return self::FAILURE;
        }

        if ($team->personal_team) {
            $this->error('Cannot export personal team configuration.');

            return self::FAILURE;
        }

        $this->info("Exporting configuration for team: {$team->name}");

        // Export the configuration
        $json = $exporter->exportAsJson($team);

        // Determine output path
        $outputPath = $this->option('output');

        if (! $outputPath) {
            $outputPath = storage_path('app/' . $exporter->generateFilename($team));
        }

        // Ensure directory exists
        $directory = dirname($outputPath);
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Write to file
        File::put($outputPath, $json);

        $this->info("Configuration exported successfully to: {$outputPath}");

        // Show statistics
        $data = json_decode($json, true);
        $this->newLine();
        $this->line('<fg=cyan>Export Statistics:</>');
        $this->line("  Print Servers: " . count($data['print_servers'] ?? []));
        $this->line("  Printers: " . collect($data['print_servers'] ?? [])->sum(fn ($server) => count($server['printers'] ?? [])));
        $this->line("  Client Applications: " . count($data['client_applications'] ?? []));

        return self::SUCCESS;
    }
}
