<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\Printer
 *
 * @property int $id
 * @property string $uuid
 * @property int $print_server_id
 * @property string $name
 * @property bool $ppd_support
 * @property array|null $ppd_options
 * @property array|null $ppd_options_layout
 * @property array $raw_languages_supported
 * @property string $uri
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PrintJobPromise[] $AvailableToJobPromises
 * @property-read int|null $available_to_job_promises_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ClientApplication[] $ClientApplications
 * @property-read int|null $client_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PrintJobPromise[] $JobPromises
 * @property-read int|null $job_promises_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PrintJob[] $Jobs
 * @property-read int|null $jobs_count
 * @property-read \App\Models\PrintServer $Server
 * @method static Builder|Printer forType(string $type)
 * @method static Builder|Printer newModelQuery()
 * @method static Builder|Printer newQuery()
 * @method static Builder|Printer query()
 * @method static Builder|Printer whereCreatedAt($value)
 * @method static Builder|Printer whereGuid($guid)
 * @method static Builder|Printer whereId($value)
 * @method static Builder|Printer whereName($value)
 * @method static Builder|Printer wherePpdOptions($value)
 * @method static Builder|Printer wherePpdOptionsLayout($value)
 * @method static Builder|Printer wherePpdSupport($value)
 * @method static Builder|Printer wherePrintServerId($value)
 * @method static Builder|Printer whereRawLanguagesSupported($value)
 * @method static Builder|Printer whereUpdatedAt($value)
 * @method static Builder|Printer whereUri($value)
 * @method static Builder|Printer whereUuid($value)
 * @mixin \Eloquent
 */
class Printer extends Model
{
    use Uuidable;

    protected $casts = [
        'ppd_options' => 'json',
        'ppd_options_layout' => 'json',
        'raw_languages_supported' => 'json',
        'ppd_support' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function Server(): BelongsTo
    {
        return $this->belongsTo(PrintServer::class, 'print_server_id');
    }

    public function Jobs(): HasMany
    {
        return $this->hasMany(PrintJob::class, 'printer_id');
    }

    public function JobPromises(): HasMany
    {
        return $this->hasMany(PrintJobPromise::class, 'printer_id');
    }

    public function AvailableToJobPromises(): BelongsToMany
    {
        return $this->belongsToMany(PrintJobPromise::class, 'pivot_print_job_printer');
    }

    public function ClientApplications(): BelongsToMany
    {
        return $this->belongsToMany(ClientApplication::class, 'pivot_client_application_printer');
    }

    public function scopeForType(Builder $query, string $type)
    {
        if($type == 'ppd')
            return $query->where('ppd_support', 1);
        else
            return $query->where(function (Builder $query) use ($type) {
                $query->whereJsonContains('raw_languages_supported', $type)
                    ->orWhereJsonContains('raw_languages_supported', '*');
            });
    }
}
