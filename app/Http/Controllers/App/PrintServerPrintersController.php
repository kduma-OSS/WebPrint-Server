<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Printer;
use App\Models\PrintServer;
use Illuminate\Http\Request;

class PrintServerPrintersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Printer::class, 'printer');
    }

    public function index(PrintServer $server)
    {
        $this->authorize('viewAny', [Printer::class, $server]);

        $printers = $server->printers()
            ->orderBy('name')
            ->withCount('jobs')
            ->get();

        return view('app.print-servers.printers.index', [
            'server' => $server,
            'printers' => $printers,
        ]);
    }

    public function create(PrintServer $server)
    {
        $this->authorize('create', [Printer::class, $server]);
    }

    public function store(Request $request, PrintServer $server)
    {
        //
    }

    public function show(Printer $printer)
    {
        //
    }

    public function edit(Printer $printer)
    {
        //
    }

    public function update(Request $request, Printer $printer)
    {
        //
    }

    public function destroy(Printer $printer)
    {
        //
    }
}
