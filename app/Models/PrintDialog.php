<?php

namespace App\Models;

use App\Models\Enums\PrintDialogStatusEnum;
use App\Models\Enums\PrintJobPromiseStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\URL;

class PrintDialog extends Model
{
    use HasUlidField;

    protected $casts = [
        'auto_print' => 'boolean',
        'status' => PrintDialogStatusEnum::class,
    ];

    public function JobPromise(): BelongsTo
    {
        return $this->belongsTo(PrintJobPromise::class, 'print_job_promise_id');
    }

    public function getLinkAttribute(): string
    {
        return URL::temporarySignedRoute(
            'api.web-print.print-dialog',
            $this->created_at->clone()->addHours(6),
            $this
        );
    }

    public function getIsActiveAttribute()
    {
        return $this->status == PrintDialogStatusEnum::New
            && $this->JobPromise->status == PrintJobPromiseStatusEnum::New
            && now()->isBefore($this->created_at->clone()->addHours(6));
    }
}
