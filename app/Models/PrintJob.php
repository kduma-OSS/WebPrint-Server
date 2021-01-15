<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\PrintJob
 *
 * @property int $id
 * @property string $uuid
 * @property string $status
 * @property string|null $status_message
 * @property int $printer_id
 * @property int|null $client_application_id
 * @property string $name
 * @property bool $ppd
 * @property array|null $ppd_options
 * @property string|null $content
 * @property string|null $content_file
 * @property string $file_name
 * @property int $size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClientApplication|null $ClientApplication
 * @property-read \App\Models\PrintJobPromise|null $JobPromise
 * @property-read \App\Models\Printer $Printer
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereClientApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereContentFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob wherePpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob wherePpdOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob wherePrinterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereStatusMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJob whereUuid($value)
 * @mixin \Eloquent
 */
class PrintJob extends Model
{
    use Uuidable;

    protected $casts = [
        'ppd_options' => 'json',
        'ppd' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

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
