<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\PrintJobPromise
 *
 * @property int $id
 * @property string $uuid
 * @property string $status
 * @property int $client_application_id
 * @property int|null $print_job_id
 * @property int|null $printer_id
 * @property string $name
 * @property string $type
 * @property array|null $ppd_options
 * @property string|null $content
 * @property string|null $content_file
 * @property string|null $file_name
 * @property int|null $size
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Printer[] $AvailablePrinters
 * @property-read int|null $available_printers_count
 * @property-read \App\Models\ClientApplication $ClientApplication
 * @property-read \App\Models\PrintJob|null $PrintJob
 * @property-read \App\Models\Printer|null $Printer
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereClientApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereContentFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise wherePpdOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise wherePrintJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise wherePrinterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintJobPromise whereUuid($value)
 * @mixin \Eloquent
 */
class PrintJobPromise extends Model
{
    use Uuidable;

    protected $casts = [
        'ppd_options' => 'json',
        'meta' => 'json',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

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

    public function AvailablePrinters(): BelongsToMany
    {
        return $this->belongsToMany(Printer::class, 'pivot_print_job_printer');
    }

    public function isReadyToPrint(): bool
    {
        return $this->status == 'ready' && $this->isPossibleToPrint();
    }

    public function isPossibleToPrint()
    {
        return in_array($this->status, ['ready', 'new']) && ($this->content || $this->content_file) && $this->printer_id;
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
        $this->status = 'sent_to_printer';
        $this->save();

        return $job;
    }
}
