<?php

namespace App\Models;

use App\Models\Enums\PrintJobPromiseStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperPrintJobPromise
 */
class PrintJobPromise extends Model
{
    use HasUlidField;

    protected $casts = [
        'ppd_options' => 'json',
        'meta' => 'json',
        'status' => PrintJobPromiseStatusEnum::class,
    ];

    public function Printer(): BelongsTo
    {
        return $this->belongsTo(Printer::class, 'printer_id');
    }

    public function PrintJob(): BelongsTo
    {
        return $this->belongsTo(PrintJob::class, 'print_job_id');
    }

    public function ClientApplication(): BelongsTo
    {
        return $this->belongsTo(ClientApplication::class, 'client_application_id');
    }

    public function PrintDialog(): HasOne
    {
        return $this->hasOne(PrintDialog::class, 'print_job_promise_id');
    }

    public function AvailablePrinters(): BelongsToMany
    {
        return $this->belongsToMany(Printer::class, 'pivot_print_job_printer');
    }

    public function isReadyToPrint(): bool
    {
        return $this->status == PrintJobPromiseStatusEnum::Ready && $this->isPossibleToPrint();
    }

    public function isPossibleToPrint()
    {
        return in_array($this->status, [
            PrintJobPromiseStatusEnum::Ready, PrintJobPromiseStatusEnum::New
            ]) && ($this->content || $this->content_file) && $this->printer_id;
    }

    public function sendForPrinting(): ?PrintJob
    {
        if(!$this->isReadyToPrint())
            return null;


        $job = new PrintJob;
        $job->client_application_id = $this->client_application_id;
        $job->printer_id = $this->printer_id;
        $job->name = $this->name;
        $job->ppd = $this->type == 'ppd';
        $job->ppd_options = $this->ppd_options;
        $job->content = $this->content;
        $job->content_file = $this->content_file;
        $job->file_name = $this->file_name;
        $job->size = $this->size;
        $job->save();

        $this->print_job_id = $job->id;
        $this->status = PrintJobPromiseStatusEnum::SentToPrinter;
        $this->save();

        return $job;
    }
}
