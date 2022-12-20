<?php

namespace App\Policies;

use App\Models\ClientApplication;
use App\Models\Enums\PrintJobStatusEnum;
use App\Models\PrintJob;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrintJobsPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any print jobs.
     *
     * @return bool
     */
    public function viewAny(mixed $user)
    {
        if ($user instanceof PrintServer) {
            return true;
        }

        if ($user instanceof User) {
            return ! $user->currentTeam->personal_team
                && $user->hasTeamPermission($user->currentTeam, 'job:read')
                && $user->tokenCan('job:read');
        }

        if ($user instanceof ClientApplication) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the print job.
     *
     * @return bool
     */
    public function view(mixed $user, PrintJob $printJob)
    {
        if ($user instanceof PrintServer) {
            return $printJob->Printer->Server->is($user);
        }

        if ($user instanceof ClientApplication) {
            return $printJob->ClientApplication?->is($user);
        }

        if ($user instanceof User) {
            return $user->belongsToTeam($printJob->Printer->Server->Team)
                && $user->hasTeamPermission($printJob->Printer->Server->Team, 'job:read')
                && $user->tokenCan('job:read');
        }
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function viewField(mixed $user, PrintJob $printJob, string $field)
    {
        // fields = 'timestamps'

        if ($user instanceof ClientApplication) {
            return $this->view($user, $printJob)
                && in_array($field, ['timestamps']);
        }

        if ($user instanceof User) {
            return ! $user->currentTeam->personal_team;
        }
    }

    /**
     * Determine whether the user can create print jobs.
     *
     * @return bool
     */
    public function create(mixed $user)
    {
        if ($user instanceof ClientApplication) {
            return true;
        }

        if ($user instanceof User) {
            return ! $user->currentTeam->personal_team
                && $user->hasTeamPermission($user->currentTeam, 'job:read')
                && $user->tokenCan('job:read');
        }
    }

    /**
     * Determine whether the user can update the print job.
     *
     * @return bool
     */
    public function update(mixed $user, PrintJob $printJob)
    {
        if ($user instanceof PrintServer) {
            return $printJob->Printer->Server->is($user);
        }

        if ($user instanceof User) {
            return $user->belongsToTeam($printJob->Printer->Server->Team)
                && $user->hasTeamPermission($printJob->Printer->Server->Team, 'job:update')
                && $user->tokenCan('job:update')
                && in_array($printJob->status, [
                    PrintJobStatusEnum::New,
                ]);
        }
    }

    /**
     * Determine whether the user can delete the print job.
     *
     * @return bool
     */
    public function delete(mixed $user, PrintJob $printJob)
    {
        if ($user instanceof User) {
            return $user->belongsToTeam($printJob->Printer->Server->Team)
                && $user->hasTeamPermission($printJob->Printer->Server->Team, 'job:delete')
                && $user->tokenCan('job:delete')
                && in_array($printJob->status, [
                    PrintJobStatusEnum::New,
                ]);
        }
    }

    /**
     * Determine whether the user can restore the print job.
     *
     * @return bool
     */
    public function restore(mixed $user, PrintJob $printJob)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the print job.
     *
     * @return bool
     */
    public function forceDelete(mixed $user, PrintJob $printJob)
    {
        //
    }
}
