<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

trait HasUlidField
{
    use HasUlids;

    /**
     * Get the columns that should receive a unique identifier.
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
