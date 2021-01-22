<?php


namespace App\Http\Controllers\WebPrintApi;


use App\Http\Controllers\Controller;
use App\Models\PrintDialog;

class UserPrintDialogController extends Controller
{
    public function __invoke(PrintDialog $dialog)
    {
        $dialog->load([
            'JobPromise',
            'JobPromise.Printer',
            'JobPromise.PrintJob',
            'JobPromise.ClientApplication',
            'JobPromise.AvailablePrinters',
        ]);

        ddd($dialog->toArray());
    }
}
