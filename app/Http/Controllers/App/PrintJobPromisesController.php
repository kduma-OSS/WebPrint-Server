<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\PrintJobPromise;
use Illuminate\Http\Request;

class PrintJobPromisesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PrintJobPromise::class, 'promise');
    }

    public function index(Request $request)
    {
        $clients = $request->user()
            ->currentTeam
            ->ClientApplications()
            ->pluck('id');

        $promises = PrintJobPromise::whereIn('client_application_id', $clients)
            ->with([
                'Printer',
                'PrintJob',
                'ClientApplication',
                'PrintDialog',
            ])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate();

        return view('app.print-job-promises.index', [
            'promises' => $promises,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(PrintJobPromise $promise)
    {
        //
    }

    public function edit(PrintJobPromise $promise)
    {
        //
    }

    public function update(Request $request, PrintJobPromise $promise)
    {
        //
    }

    public function destroy(PrintJobPromise $promise)
    {
        //
    }
}
