<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\URL;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\PrintDialog
 *
 * @property int $id
 * @property string $uuid
 * @property string $status
 * @property int $print_job_promise_id
 * @property bool $auto_print
 * @property string|null $redirect_url
 * @property string|null $restricted_ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PrintJobPromise $JobPromise
 * @property-read mixed $is_active
 * @property-read string $link
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereAutoPrint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog wherePrintJobPromiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereRedirectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereRestrictedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintDialog whereUuid($value)
 * @mixin \Eloquent
 */
class PrintDialog extends Model
{
    use Uuidable;

    protected $casts = [
        'auto_print' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function JobPromise(): BelongsTo
    {
        return $this->belongsTo(PrintJobPromise::class, 'print_job_promise_id');
    }

    public function getLinkAttribute(): string
    {
        return URL::temporarySignedRoute('api.web-print.print-dialog', $this->created_at->clone()->addHours(6), $this);
    }

    public function getIsActiveAttribute()
    {
        return $this->status == 'new' && $this->JobPromise->status == 'new' && now()->isBefore($this->created_at->clone()->addHours(6));
    }
}
