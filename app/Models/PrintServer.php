<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Sanctum\HasApiTokens;

class PrintServer extends Model implements AuthorizableContract
{
    use HasApiTokens;
    Use Authorizable;
    use HasUlidField;

    public function Printers(): HasMany
    {
        return $this->hasMany(Printer::class, 'print_server_id');
    }

    public function Jobs(): HasManyThrough
    {
        return $this->hasManyThrough(PrintJob::class, Printer::class, 'print_server_id', 'printer_id');
    }
}
