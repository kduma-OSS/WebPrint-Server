<?php

namespace App\Policies;

use App\Models\ClientApplication;
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
     * @param  mixed  $user
     * @return bool
     */
    public function viewAny(mixed $user)
    {
        if ($user instanceof User) {
            return ! $user->currentTeam->personal_team;
        }
    }

    /**
     * Determine whether the user can view the client application.
     *
     * @param  mixed  $user
     * @param  ClientApplication  $client
     * @return bool
     */
    public function view(mixed $user, ClientApplication $client)
    {
    }

    /**
     * @param  mixed  $user
     * @param  ClientApplication  $client
     * @param  string  $field
     * @return bool
     *
     * @throws \Exception
     */
    public function viewField(mixed $user, ClientApplication $client, string $field)
    {
    }

    /**
     * Determine whether the user can create client applications.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function create(mixed $user)
    {
    }

    /**
     * Determine whether the user can update the client application.
     *
     * @param  mixed  $user
     * @param  ClientApplication  $client
     * @return bool
     */
    public function update(mixed $user, ClientApplication $client)
    {
    }

    /**
     * Determine whether the user can delete the client application.
     *
     * @param  mixed  $user
     * @param  ClientApplication  $client
     * @return bool
     */
    public function delete(mixed $user, ClientApplication $client)
    {
        //
    }

    /**
     * Determine whether the user can restore the client application.
     *
     * @param  mixed  $user
     * @param  ClientApplication  $client
     * @return bool
     */
    public function restore(mixed $user, ClientApplication $client)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the client application.
     *
     * @param  mixed  $user
     * @param  ClientApplication  $client
     * @return bool
     */
    public function forceDelete(mixed $user, ClientApplication $client)
    {
        //
    }
}
