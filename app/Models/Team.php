<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

/**
 * @mixin IdeHelperTeam
 */
class Team extends JetstreamTeam
{
    use HasUlidField;
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function PrintServers(): HasMany
    {
        return $this->hasMany(PrintServer::class, 'team_id');
    }

    public function Printers(): HasManyThrough
    {
        return $this->hasManyThrough(Printer::class, PrintServer::class, 'team_id', 'print_server_id');
    }

    public function ClientApplications(): HasMany
    {
        return $this->hasMany(ClientApplication::class, 'team_id');
    }
}
