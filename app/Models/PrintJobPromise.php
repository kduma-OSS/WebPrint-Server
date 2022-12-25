<?php

namespace App\Models;

use App\Models\Enums\PrintJobPromiseStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;

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
}
