<?php

namespace App\Models;

use App\Models\Traits\ArnDefaultsTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Sanctum\HasApiTokens;
use RenokiCo\Acl\Concerns\HasPolicies;
use RenokiCo\Acl\Contracts\RuledByPolicies;

/**
 * @mixin IdeHelperClientApplication
 */
class ClientApplication extends Model implements AuthorizableContract, AuthenticatableContract, RuledByPolicies
{
    use HasFactory;
    use HasApiTokens;
    use Authorizable;
    use HasUlidField;
    use Authenticatable;
    use HasPolicies;

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

    public function resolveArnAccountId()
    {
        return $this->Team->ulid;
    }

    public function resolveArnRegion()
    {
        return 'local';
    }
}
