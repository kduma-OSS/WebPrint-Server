<?php

namespace App\Models;

use App\Models\Enums\PrintJobStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperPrintJob
 */
class PrintJob extends Model
{
    use HasUlidField;

    protected $casts = [
        'ppd_options' => 'json',
        'ppd' => 'boolean',
        'status' => PrintJobStatusEnum::class,
    ];

    public function Printer(): BelongsTo
    {
        return $this->belongsTo(Printer::class, 'printer_id');
    }

    public function JobPromise(): HasOne
    {
        return $this->hasOne(PrintJobPromise::class, 'print_job_id');
    }

    public function ClientApplication(): BelongsTo
    {
        return $this->belongsTo(ClientApplication::class, 'client_application_id');
    }
}
