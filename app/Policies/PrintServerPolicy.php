<?php

namespace App\Policies;

use App\Models\ClientApplication;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrintServerPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any print servers.
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
     * Determine whether the user can view the print server.
     *
     * @param  mixed  $user
     * @param  PrintServer  $printServer
     * @return bool
     */
    public function view(mixed $user, PrintServer $printServer)
    {
        if ($user instanceof ClientApplication) {
            return $user->Printers->pluck('print_server_id')->contains($printServer->id);
        }
    }

    /**
     * @param  mixed  $user
     * @param  PrintServer  $printServer
     * @param  string  $field
     * @return bool
     *
     * @throws \Exception
     */
    public function viewField(mixed $user, PrintServer $printServer, string $field)
    {
        // fields = 'timestamps'

        if ($user instanceof ClientApplication) {
            return $this->view($user, $printServer)
                && in_array($field, []);
        }
    }

    /**
     * Determine whether the user can create print servers.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function create(mixed $user)
    {
        //
    }

    /**
     * Determine whether the user can update the print server.
     *
     * @param  mixed  $user
     * @param  PrintServer  $printServer
     * @return bool
     */
    public function update(mixed $user, PrintServer $printServer)
    {
        //
    }

    /**
     * Determine whether the user can delete the print server.
     *
     * @param  mixed  $user
     * @param  PrintServer  $printServer
     * @return bool
     */
    public function delete(mixed $user, PrintServer $printServer)
    {
        //
    }

    /**
     * Determine whether the user can restore the print server.
     *
     * @param  mixed  $user
     * @param  PrintServer  $printServer
     * @return bool
     */
    public function restore(mixed $user, PrintServer $printServer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the print server.
     *
     * @param  mixed  $user
     * @param  PrintServer  $printServer
     * @return bool
     */
    public function forceDelete(mixed $user, PrintServer $printServer)
    {
        //
    }
}
