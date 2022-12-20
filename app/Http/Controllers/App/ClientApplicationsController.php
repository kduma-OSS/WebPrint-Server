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

    public function index(Request $request)
    {
        $apps = $request->user()
            ->currentTeam
            ->ClientApplications()
            ->withCount('jobs')
            ->get();

        return view('app.client-applications.index', [
            'apps' => $apps,
        ]);
    }

    public function create(): void
    {
        //
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(ClientApplication $app): void
    {
        //
    }

    public function edit(ClientApplication $app): void
    {
        //
    }

    public function update(Request $request, ClientApplication $app): void
    {
        //
    }

    public function destroy(ClientApplication $app): void
    {
        //
    }
}
