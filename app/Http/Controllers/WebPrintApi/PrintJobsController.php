<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Actions\Promises\CheckPromiseAbilityToBePrintedAction;
use App\Actions\Promises\ConvertPromiseToJobAction;
use App\Http\Controllers\Controller;
use App\Models\ClientApplication;
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

        $this->middleware(function (Request $request, $next) {
            /** @var ClientApplication $client_application */
            $client_application = $request->user();

            abort_if($client_application instanceof ClientApplication === false, 403);

            $client_application->last_active_at = now();
            $client_application->save();

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        CheckPromiseAbilityToBePrintedAction $checkPromiseAbilityToBePrintedAction,
        ConvertPromiseToJobAction $convertPromiseToJobAction
    )
    {
        $validated = $request->validate([
            'promise' => ['required', Rule::exists('print_job_promises', 'ulid')
                ->where('client_application_id', $request->user()->id), ],
        ]);

        $promise = PrintJobPromise::where('ulid', $validated['promise'])->firstOrFail();

        abort_unless($checkPromiseAbilityToBePrintedAction->handle($promise), 412);
        if ($promise->status != PrintJobPromiseStatusEnum::Ready) {
            $promise->status = PrintJobPromiseStatusEnum::Ready;
            $promise->save();
        }

        $convertPromiseToJobAction->handle($promise);

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(PrintJob $job): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrintJob $job): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrintJob $job): void
    {
        //
    }
}
