<?php

namespace App\Http\Controllers\PrintServiceApi;

use App\Http\Controllers\Controller;
use App\Models\PrintJob;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\HeaderUtils;

class PrintJobContentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  PrintJob  $job
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\StreamedResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(PrintJob $job)
    {
        if (! $job->content && ! $job->content_file) {
            return response(status: 404);
        }

        if (is_null($job->content)) {
            return \Storage::download($job->content_file, $job->file_name, [
                'Content-Type' => 'application/octet-stream',
            ]);
        } else {
            return response($job->content, 200, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => HeaderUtils::makeDisposition('attachment', $job->file_name, Str::slug($job->file_name, '.')),
            ]);
        }
    }
}
