<?php

namespace App\Console\Commands;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ulid\Ulid;

class ExportDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all data for importing in new version';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(FilesystemManager $filesystemManager, DatabaseManager $db)
    {
        $storage = $filesystemManager->build([
            'root' => storage_path('app/export'),
        ]);

        $storage->delete($storage->allFiles());

        $this->exportTable('client_applications', $storage, fn($client) => [
            'id'         => $client->id,
            '_uuid'      => $client->uuid,
            'ulid'       => (string)Ulid::fromTimestamp(strtotime($client->created_at)),

            'name'       => $client->name,
            'created_at' => $client->created_at,
            'updated_at' => $client->updated_at,
        ]);

        $this->exportTable('pivot_print_job_printer', $storage, fn($pivot) => [
            'id'                   => $pivot->id,

            'print_job_promise_id' => $pivot->print_job_promise_id,
            'printer_id'           => $pivot->printer_id,
        ]);

        $this->exportTable('pivot_client_application_printer', $storage, fn($pivot) => [
            'id'                    => $pivot->id,

            'client_application_id' => $pivot->client_application_id,
            'printer_id'            => $pivot->printer_id,
        ]);

        $this->exportTable('printers', $storage, fn($printer) => [
            'id'                      => $printer->id,
            '_uuid'                   => $printer->uuid,
            'ulid'                    => (string)Ulid::fromTimestamp(strtotime($printer->created_at)),

            'print_server_id'         => $printer->print_server_id,
            'name'                    => $printer->name,
            'location'                => $printer->location,
            'enabled'                 => $printer->enabled,
            'ppd_support'             => $printer->ppd_support,
            'ppd_options'             => $printer->ppd_options,
            'raw_languages_supported' => $printer->raw_languages_supported,
            'uri'                     => $printer->uri,
            'created_at'              => $printer->created_at,
            'updated_at'              => $printer->updated_at,
        ]);

        $this->exportTable('print_servers', $storage, fn($server) => [
            'id'    => $server->id,
            '_uuid' => $server->uuid,
            'ulid'  => (string)Ulid::fromTimestamp(strtotime($server->created_at)),

            'name'       => $server->name,
            'created_at' => $server->created_at,
            'updated_at' => $server->updated_at,
        ]);

        $this->exportTable('print_jobs', $storage, function ($job) use ($storage) {
            if ($job->content_file && Storage::exists($job->content_file)) {
                $storage->writeStream('print_job_promises/' . $job->id . '.data', Storage::readStream($job->content_file));
            }

            return [
                'id'    => $job->id,
                '_uuid' => $job->uuid,
                'ulid'  => (string)Ulid::fromTimestamp(strtotime($job->created_at)),

                'status'                => $job->status,
                'status_message'        => $job->status_message,
                'printer_id'            => $job->printer_id,
                'client_application_id' => $job->client_application_id,
                'name'                  => $job->name,
                'ppd'                   => $job->ppd,
                'ppd_options'           => $job->ppd_options,
                'content'               => $job->content,
                'content_file'          => $job->content_file,
                'file_name'             => $job->file_name,
                'size'                  => $job->size,
                'created_at'            => $job->created_at,
                'updated_at'            => $job->updated_at,
            ];
        });

        $this->exportTable('print_dialogs', $storage, fn($dialog) => [
            'id'    => $dialog->id,
            '_uuid' => $dialog->uuid,
            'ulid'  => (string)Ulid::fromTimestamp(strtotime($dialog->created_at)),

            'status'               => $dialog->status,
            'print_job_promise_id' => $dialog->print_job_promise_id,
            'auto_print'           => $dialog->auto_print,
            'redirect_url'         => $dialog->redirect_url,
            'restricted_ip'        => $dialog->restricted_ip,
            'created_at'           => $dialog->created_at,
            'updated_at'           => $dialog->updated_at,
        ]);

        $this->exportTable('personal_access_tokens', $storage, fn($token) => [
            'id' => $token->id,

            'tokenable_type' => match ($token->tokenable_type) {
                'App\Models\ClientApplication' => 'client_applications',
                'App\Models\PrintServer' => 'print_servers',
                default => $token->tokenable_type
            },
            'tokenable_id'   => $token->tokenable_id,
            'name'           => $token->name,
            'token'          => $token->token,
            'abilities'      => $token->abilities,
            'last_used_at'   => $token->last_used_at,
            'created_at'     => $token->created_at,
            'updated_at'     => $token->updated_at,
        ]);

        $this->exportTable('print_job_promises', $storage, function ($promise) use ($storage) {
            if ($promise->content_file && Storage::exists($promise->content_file)) {
                $storage->writeStream('print_job_promises/' . $promise->id . '.data', Storage::readStream($promise->content_file));
            }

            return [
                'id'    => $promise->id,
                '_uuid' => $promise->uuid,
                'ulid'  => (string)Ulid::fromTimestamp(strtotime($promise->created_at)),

                'status'                => $promise->status,
                'client_application_id' => $promise->client_application_id,
                'print_job_id'          => $promise->print_job_id,
                'printer_id'            => $promise->printer_id,
                'name'                  => $promise->name,
                'type'                  => $promise->type,
                'ppd_options'           => $promise->ppd_options,
                'content'               => $promise->content,
                'content_file'          => $promise->content_file,
                'file_name'             => $promise->file_name,
                'size'                  => $promise->size,
                'meta'                  => $promise->meta,
                'created_at'            => $promise->created_at,
                'updated_at'            => $promise->updated_at,
            ];
        });
    }

    protected function exportTable($table_name, Filesystem|FilesystemAdapter $storage, Closure $callback)
    {
        $counter = 0;

        DB::query()
            ->from($table_name)
            ->orderBy('id')
            ->chunkById(100, function ($items) use (&$counter, $table_name, $storage, $callback) {
                $storage->put(
                    $table_name . ($counter ? '-' . $counter : '') . '.json',
                    json_encode($items->map($callback), JSON_PRETTY_PRINT)
                );
                $counter++;
            });
    }
}
