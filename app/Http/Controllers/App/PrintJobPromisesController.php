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
