<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

trait HasUlidField
{
    use HasUlids;

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds()
    {
        return ['ulid'];
    }
}
