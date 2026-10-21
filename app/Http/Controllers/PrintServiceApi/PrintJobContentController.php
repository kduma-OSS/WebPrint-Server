<?php

namespace App\Http\Controllers\PrintServiceApi;

use App\Http\Controllers\Controller;
use App\Models\PrintJob;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrintJobContentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return Response|StreamedResponse
     *
     * @throws AuthorizationException
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
        }

        return response($job->content, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => HeaderUtils::makeDisposition('attachment', $job->file_name, Str::slug($job->file_name, '.')),
        ]);
    }
}
