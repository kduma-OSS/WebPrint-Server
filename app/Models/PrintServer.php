<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperPrintServer
 */
class PrintServer extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasApiTokens;
    use Authorizable;
    use HasFactory;
    use HasUlidField;
    use Authenticatable;

    public function getRememberTokenName()
    {
        return null;
    }

    protected $casts = [
        'last_active_at' => 'datetime',
    ];

    public function Printers(): HasMany
    {
        return $this->hasMany(Printer::class, 'print_server_id');
    }

    public function Team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function Jobs(): HasManyThrough
    {
        return $this->hasManyThrough(PrintJob::class, Printer::class, 'print_server_id', 'printer_id');
    }
}
