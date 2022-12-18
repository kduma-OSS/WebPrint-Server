<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperClientApplication
 */
class ClientApplication extends Model implements AuthorizableContract
{
    use HasApiTokens;
    use Authorizable;
    use HasUlidField;

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
