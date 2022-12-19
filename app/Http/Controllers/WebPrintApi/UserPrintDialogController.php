<?php

namespace App\Http\Controllers\WebPrintApi;

use App\Http\Controllers\Controller;
use App\Models\ClientApplication;
use App\Models\PrintDialog;
use Illuminate\Http\Request;

class UserPrintDialogController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            /** @var ClientApplication $client_application */
            $client_application = $request->user();

            abort_if($client_application instanceof ClientApplication === false, 403);

            $client_application->last_active_at = now();
            $client_application->save();

            return $next($request);
        });
    }

    public function __invoke(PrintDialog $dialog, Request $request)
    {
        if (! $dialog->restricted_ip || $request->ip() == $dialog->restricted_ip) {
            return view('webprint-api.print-dialog', ['dialog' => $dialog]);
        }

        return response()->view('webprint-api.ip-error', ['dialog' => $dialog], 451);
    }
}
