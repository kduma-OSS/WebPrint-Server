<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use KDuma\Eloquent\Uuidable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\ClientApplication
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PrintJobPromise[] $JobPromises
 * @property-read int|null $job_promises_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PrintJob[] $Jobs
 * @property-read int|null $jobs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Printer[] $Printers
 * @property-read int|null $printers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientApplication whereUuid($value)
 * @mixin \Eloquent
 */
class ClientApplication extends Model implements AuthorizableContract
{
    use HasApiTokens, Authorizable, Uuidable;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function Printers(): BelongsToMany
    {
        return $this->belongsToMany(Printer::class, 'pivot_client_application_printer');
    }

    public function JobPromises(): HasMany
    {
        return $this->hasMany(PrintJobPromise::class, 'client_application_id');
    }

    public function Jobs(): HasMany
    {
        return $this->hasMany(PrintJob::class, 'client_application_id');
    }
}
