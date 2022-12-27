<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\PrintServer;
use Illuminate\Http\Request;

class PrintServersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PrintServer::class, 'server');
    }

    public function index(Request $request)
    {
        $servers = $request->user()->currentTeam->PrintServers()
            ->orderBy('name')
            ->withCount('printers')
            ->get();

        return view('app.print-servers.index', [
            'servers' => $servers,
        ]);
    }

    public function create()
    {
        return view('app.print-servers.create');
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(PrintServer $server)
    {
        return view('app.print-servers.show', [
            'server' => $server,
        ]);
    }

    public function edit(PrintServer $server): void
    {
        //
    }

    public function update(Request $request, PrintServer $server): void
    {
        //
    }

    public function destroy(PrintServer $server): void
    {
        //
    }
}
