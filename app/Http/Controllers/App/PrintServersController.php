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

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(PrintServer $server)
    {
        //
    }

    public function edit(PrintServer $server)
    {
        //
    }

    public function update(Request $request, PrintServer $server)
    {
        //
    }

    public function destroy(PrintServer $server)
    {
        //
    }
}
