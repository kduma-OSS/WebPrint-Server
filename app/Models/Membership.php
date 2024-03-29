<?php

namespace App\Models;

use Laravel\Jetstream\Membership as JetstreamMembership;

/**
 * @mixin IdeHelperMembership
 */
class Membership extends JetstreamMembership
{
    use HasUlidField;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
