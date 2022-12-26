<?php

namespace App\Actions\Jobs;

use App\Models\PrintJob;
use Illuminate\Support\Facades\Storage;

class ClearJobContentAction
{
    public function handle(PrintJob $job)
    {
        if ($job->content_file && Storage::exists($job->content_file)) {
            Storage::delete($job->content_file);
        }

        $job->content = null;
        $job->size = 0;
        $job->content_file = null;

        $job->save();
    }
}
