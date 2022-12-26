<?php

namespace App\Policies;

use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrintersPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any printers.
     *
     * @param  PrintServer|null  $server
     * @return bool
     */
    public function viewAny(mixed $user, PrintServer $server = null)
    {
        if ($user instanceof ClientApplication) {
            return true;
        }

        if ($user instanceof User) {
            $team = $server !== null ? $server->Team : $user->currentTeam;

            return ! $team->personal_team
                && $user->hasTeamPermission($team, 'printer:read')
                && $user->tokenCan('printer:read');
        }
    }

    /**
     * Determine whether the user can view the printer.
     *
     * @return bool
     */
    public function view(mixed $user, Printer $printer)
    {
        if ($user instanceof ClientApplication) {
            return $user->Printers->contains($printer);
        }

        if ($user instanceof User) {
            return $user->belongsToTeam($printer->Server->Team)
                && $user->hasTeamPermission($printer->Server->Team, 'printer:read')
                && $user->tokenCan('printer:read');
        }
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function viewField(mixed $user, Printer $printer, string $field)
    {
        // fields = 'server', 'raw_languages_supported', 'ppd', 'uri', 'timestamps', 'location'

        if ($user instanceof ClientApplication) {
            return $this->view($user, $printer)
                && in_array($field, ['server', 'raw_languages_supported', 'ppd', 'location']);
        }

        if ($user instanceof User) {
            return ! $user->currentTeam->personal_team;
        }
    }

    /**
     * Determine whether the user can create printers.
     *
     * @param  PrintServer|null  $server
     * @return bool
     */
    public function create(mixed $user, PrintServer $server = null)
    {
        if ($user instanceof User) {
            $team = $server !== null ? $server->Team : $user->currentTeam;

            return ! $team->personal_team
                && $user->hasTeamPermission($team, 'printer:create')
                && $user->tokenCan('printer:create');
        }
    }

    /**
     * Determine whether the user can update the printer.
     *
     * @return bool
     */
    public function update(mixed $user, Printer $printer)
    {
        if ($user instanceof User) {
            return $user->belongsToTeam($printer->Server->Team)
                && $user->hasTeamPermission($printer->Server->Team, 'printer:update')
                && $user->tokenCan('printer:update');
        }
    }

    /**
     * Determine whether the user can delete the printer.
     *
     * @return bool
     */
    public function delete(mixed $user, Printer $printer)
    {
        if ($user instanceof User) {
            return $user->belongsToTeam($printer->Server->Team)
                && $user->hasTeamPermission($printer->Server->Team, 'printer:delete')
                && $user->tokenCan('printer:delete');
        }
    }

    /**
     * Determine whether the user can restore the printer.
     *
     * @return bool
     */
    public function restore(mixed $user, Printer $printer): void
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the printer.
     *
     * @return bool
     */
    public function forceDelete(mixed $user, Printer $printer): void
    {
        //
    }
}
