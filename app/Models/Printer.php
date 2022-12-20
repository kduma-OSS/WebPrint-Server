<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperPrinter
 */
class Printer extends Model
{
    use HasFactory;
    use HasUlidField;

    protected $casts = [
        'ppd_options' => 'json',
        'ppd_options_layout' => 'json',
        'raw_languages_supported' => 'json',
        'ppd_support' => 'boolean',
        'enabled' => 'boolean',
    ];

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
        if ($type == 'ppd') {
            return $query->where('ppd_support', 1);
        }
        return $query->where(function (Builder $query) use ($type) {
            $query->whereJsonContains('raw_languages_supported', $type)
                ->orWhereJsonContains('raw_languages_supported', '*');
        });
    }
}
