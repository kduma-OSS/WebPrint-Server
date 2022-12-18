<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\PrintJob;
use App\Models\PrintJobPromise;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'promise' => ['required', Rule::exists('print_job_promises', 'ulid')
                ->where('client_application_id', $request->user()->id)]
        ]);

        $promise = PrintJobPromise::where('ulid', $validated['promise'])->firstOrFail();

        abort_unless($promise->isPossibleToPrint(), 412);
        if($promise->status != PrintJobPromiseStatusEnum::Ready) {
            $promise->status = PrintJobPromiseStatusEnum::Ready;
            $promise->save();
        }

        $job = $promise->sendForPrinting();

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param PrintJob $job
     *
     * @return \Illuminate\Http\Response
     */
    public function show(PrintJob $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request  $request
     * @param PrintJob $job
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrintJob $job)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PrintJob $job
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrintJob $job)
    {
        //
    }
}
