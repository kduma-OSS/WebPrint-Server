<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperClientApplication
 */
class ClientApplication extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasFactory;
    use HasApiTokens;
    use Authorizable;
    use HasUlidField;
    use Authenticatable;

    public function getRememberTokenName()
    {
        return null;
    }

    protected $casts = [
        'last_active_at' => 'datetime',
    ];

    public function Printers(): BelongsToMany
    {
        return $this->belongsToMany(Printer::class, 'pivot_client_application_printer');
    }

    public function Team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function JobPromises(): HasMany
    {
        return $this->hasMany(PrintJobPromise::class, 'client_application_id');
    }

    public function Jobs(): HasMany
    {
        return $this->hasMany(PrintJob::class, 'client_application_id');
    }

    protected function urlDomain(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['url']
                ? parse_url($attributes['url'], PHP_URL_HOST)
                : null,
        );
    }
}
