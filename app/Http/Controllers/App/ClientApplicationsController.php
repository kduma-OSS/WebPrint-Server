<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\ClientApplication;
use Illuminate\Http\Request;

class ClientApplicationsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ClientApplication::class, 'app');
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

    public function show(ClientApplication $app)
    {
        //
    }

    public function edit(ClientApplication $app)
    {
        //
    }

    public function update(Request $request, ClientApplication $app)
    {
        //
    }

    public function destroy(ClientApplication $app)
    {
        //
    }
}
