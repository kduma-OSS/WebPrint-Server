<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\Access\Authorizable;
use KDuma\Eloquent\Uuidable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\PrintServer
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PrintJob[] $Jobs
 * @property-read int|null $jobs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Printer[] $Printers
 * @property-read int|null $printers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrintServer whereUuid($value)
 * @mixin \Eloquent
 */
class PrintServer extends Model implements AuthorizableContract
{
    use HasApiTokens, Authorizable, Uuidable;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function Printers(): HasMany
    {
        return $this->hasMany(Printer::class, 'print_server_id');
    }

    public function Jobs(): HasManyThrough
    {
        return $this->hasManyThrough(PrintJob::class, Printer::class, 'print_server_id', 'printer_id');
    }
}
