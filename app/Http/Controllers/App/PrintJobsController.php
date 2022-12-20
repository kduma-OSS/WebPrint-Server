<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\PrintJob;
use Illuminate\Http\Request;

class PrintJobsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PrintJob::class, 'job');
    }

    public function index(Request $request)
    {
        $printers = $request->user()
            ->currentTeam
            ->Printers()
            ->pluck('printers.id');

        $app = null;
        $printer = null;

        $jobs = PrintJob::whereIn('printer_id', $printers)
            ->with([
                'Printer',
                'JobPromise',
                'ClientApplication',
            ])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->when($request->has('app') && ($app = $request->user()->currentTeam->ClientApplications()->where('ulid', $request->has('app'))->first()), function ($query) use ($app): void {
                $query->where('client_application_id', $app->id);
            })
            ->when($request->has('printer') && ($printer = $request->user()->currentTeam->Printers()->where('printers.ulid', $request->has('printer'))->first()), function ($query) use ($printer): void {
                $query->where('printer_id', $printer->id);
            })
            ->paginate();

        return view('app.print-jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(PrintJob $job)
    {
    }

    public function edit(PrintJob $job)
    {
    }

    public function update(Request $request, PrintJob $job)
    {
    }

    public function destroy(PrintJob $job)
    {
    }
}
