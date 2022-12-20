<?php

namespace App\Http\Controllers\PrintServiceApi;

use App\Http\Controllers\Controller;
use App\Models\Enums\PrintJobStatusEnum;
use App\Models\PrintJob;
use App\Models\PrintServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class PrintJobsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PrintJob::class, 'job');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     */
    public function index(Request $request)
    {
        $long_polling = $request->boolean('long_poll');

        /** @var PrintServer $print_server */
        $print_server = $request->user();

        $attempts = 0;

        $print_server->last_active_at = now();
        $print_server->save();

        do {
            if ($attempts++ !== 0) {
                sleep(4);
            }

            $jobs_list = $print_server->Jobs()->where('status', 'new')
                ->oldest('print_jobs.created_at')
                ->pluck('print_jobs.ulid');
        } while ($long_polling && ! count($jobs_list) && $attempts <= 10);

        return $jobs_list;
    }

    /**
     * Display the specified resource.
     *
     * @return array|\Illuminate\Http\Response
     */
    public function show(PrintJob $job)
    {
        if ($job->status != PrintJobStatusEnum::New) {
            return response(status: 410);
        }

        if (is_null($job->content) || $job->size > 1024) {
            $content = [
                'content_type' => 'file',
                'content' => URL::temporarySignedRoute('api.print-service.jobs.content.index', now()->addHour(), $job),
            ];
        } elseif (preg_match('#[^\x20-\x7e\n]#', $job->content)) {
            $content = [
                'content_type' => 'base64',
                'content' => base64_encode(gzcompress($job->content)),
            ];
        } else {
            $content = [
                'content_type' => 'plain',
                'content' => $job->content,
            ];
        }
        $options = $job->ppd_options;
        if ($job->ppd) {
            $options = collect($job->Printer->ppd_options)
                ->mapWithKeys(function ($option, $key) use ($options) {
                    $value = isset($options[$option['key']]) ? ($options[$option['key']] ?? $option['default']) : $option['default'];

                    if ($option['type'] ?? 'select' == 'Boolean') {
                        return [$option['key'] => $value == 'True'];
                    }

                    return [$option['key'] => $value];
                })
                ->toArray();
        }

        return [
            'ulid' => $job->ulid,
            'name' => $job->name,
            'ppd' => $job->ppd,
            'file_name' => $job->file_name,
            'size' => $job->size,
            'options' => $options ?? null,
            'printer' => [
                'name' => $job->Printer->name,
                'uri' => $job->Printer->uri,
            ],
            'created_at' => $job->created_at,
        ] + $content;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrintJob $job)
    {
        $fields = $request->validate([
            'status' => ['required', Rule::in(['printing', 'finished', 'failed'])],
            'status_message' => ['required_if:status,failed'],
        ]);

        $job->status = $fields['status'];
        $job->status_message = $fields['status_message'] ?? null;
        $job->save();

        return response()->noContent();
    }
}
