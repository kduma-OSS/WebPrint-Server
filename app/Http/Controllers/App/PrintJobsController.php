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

        $jobs = PrintJob::whereIn('printer_id', $printers)
            ->with([
                'Printer',
                'JobPromise',
                'ClientApplication',
            ])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
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
