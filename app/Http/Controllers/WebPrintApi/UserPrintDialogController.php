<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Models\PrintDialog;
use Illuminate\Http\Request;

class UserPrintDialogController extends Controller
{
    public function __invoke(PrintDialog $dialog, Request $request)
    {
        if (! $dialog->restricted_ip || $request->ip() == $dialog->restricted_ip) {
            return view('webprint-api.print-dialog', ['dialog' => $dialog]);
        }

        return response()->view('webprint-api.ip-error', ['dialog' => $dialog], 451);
    }
}
