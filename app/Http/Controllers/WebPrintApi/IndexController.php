<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'name' => config('app.name'),
            'version' => config('app.version'),
            'client' => $request->user()->name,
        ]);
    }
}
