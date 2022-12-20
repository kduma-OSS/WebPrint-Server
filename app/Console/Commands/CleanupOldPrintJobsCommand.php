<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use App\Models\PrintJobPromise;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOldPrintJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'print_jobs:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup Old Print Jobs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        foreach ([
            [['draft', 'new', 'canceled'], now()->subHours(12), now()->subHours(24)],
            [['sent_to_printer', 'finished'], now()->subDays(1), now()->subDays(90)],
            [['failed'], now()->subDays(30), now()->subDays(90)],
            //                     [['ready', 'printing'], now()->subDays(90), now()->subDays(90)],
        ] as [$keys, $strip_contents, $delete]) {
            PrintJobPromise::where('updated_at', '<', $strip_contents)
                ->whereIn('status', $keys)
                ->each(function (PrintJobPromise $promise): void {
                    if ($promise->content_file) {
                        Storage::delete($promise->content_file);
                        PrintJob::where('content_file', $promise->content_file)
                            ->each(function (PrintJob $job): void {
                                $job->content_file = null;
                                $job->timestamps = false;
                                $job->save();
                            });
                        $promise->content_file = null;
                        $promise->timestamps = false;
                        $promise->save();
                    }

                    if ($promise->content) {
                        $promise->content = null;
                        $promise->timestamps = false;
                        $promise->save();
                    }
                });

            PrintJobPromise::where('updated_at', '<', $delete)
                ->whereIn('status', $keys)
                ->each(function (PrintJobPromise $promise): void {
                    if ($promise->PrintDialog) {
                        $promise->PrintDialog->delete();
                    }

                    $promise->AvailablePrinters()->sync([]);
                    $promise->delete();
                });

            PrintJob::where('updated_at', '<', $strip_contents)
                ->whereIn('status', $keys)
                ->each(function (PrintJob $job): void {
                    if ($job->content_file) {
                        Storage::delete($job->content_file);
                        $job->content_file = null;
                        $job->timestamps = false;
                        $job->save();
                    }

                    if ($job->content) {
                        $job->content = null;
                        $job->timestamps = false;
                        $job->save();
                    }
                });

            PrintJob::where('updated_at', '<', $delete)
                ->whereIn('status', $keys)
                ->each(function (PrintJob $job): void {
                    $job->delete();
                });
        }
    }
}
