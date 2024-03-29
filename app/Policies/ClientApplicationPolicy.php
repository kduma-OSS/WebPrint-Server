<?php

namespace App\Policies;

use App\Models\ClientApplication;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientApplicationPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any client applications.
     *
     * @return bool
     */
    public function viewAny(mixed $user, Team $team = null)
    {
        if ($user instanceof User) {
            $team ??= $user->currentTeam;

            return ! $team->personal_team
                && $user->hasTeamPermission($team, 'server:read')
                && $user->tokenCan('server:read');
        }
    }

    /**
     * Determine whether the user can view the client application.
     *
     * @return bool
     */
    public function view(mixed $user, ClientApplication $client)
    {
        if ($user instanceof User) {
            return $user->belongsToTeam($client->Team)
                && $user->hasTeamPermission($client->Team, 'client:read')
                && $user->tokenCan('client:read');
        }
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function viewField(mixed $user, ClientApplication $client, string $field)
    {
        if ($user instanceof User) {
            return ! $user->currentTeam->personal_team;
        }
    }

    /**
     * Determine whether the user can create client applications.
     *
     * @return bool
     */
    public function create(mixed $user, Team $team = null)
    {
        if ($user instanceof User) {
            $team ??= $user->currentTeam;

            return ! $team->personal_team
                && $user->hasTeamPermission($team, 'client:create')
                && $user->tokenCan('client:create');
        }
    }

    /**
     * Determine whether the user can update the client application.
     *
     * @return bool
     */
    public function update(mixed $user, ClientApplication $client)
    {
        if ($user instanceof User) {
            return $user->belongsToTeam($client->Team)
                && $user->hasTeamPermission($client->Team, 'client:update')
                && $user->tokenCan('client:update');
        }
    }

    /**
     * Determine whether the user can generate token for the client application.
     *
     * @return bool
     */
    public function generateToken(mixed $user, ClientApplication $client)
    {
        if ($user instanceof User) {
            return $user->belongsToTeam($client->Team)
                && $user->hasTeamPermission($client->Team, 'client:token')
                && $user->tokenCan('client:token');
        }
    }

    /**
     * Determine whether the user can delete the client application.
     *
     * @return bool
     */
    public function delete(mixed $user, ClientApplication $client)
    {
        if ($user instanceof User) {
            return $user->belongsToTeam($client->Team)
                && $user->hasTeamPermission($client->Team, 'client:delete')
                && $user->tokenCan('client:delete');
        }
    }

    /**
     * Determine whether the user can restore the client application.
     *
     * @return bool
     */
    public function restore(mixed $user, ClientApplication $client): void
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the client application.
     *
     * @return bool
     */
    public function forceDelete(mixed $user, ClientApplication $client): void
    {
        //
    }
}
