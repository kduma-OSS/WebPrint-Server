<?php

namespace App\Policies;

use App\Models\ClientApplication;
use App\Models\Printer;
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
     * @param mixed $user
     *
     * @return bool
     */
    public function viewAny(mixed $user)
    {
        if($user instanceof PrintServer)
            return true;

        if($user instanceof User)
            return true;

        if($user instanceof ClientApplication)
            return true;
    }

    /**
     * Determine whether the user can view the print job.
     *
     * @param mixed     $user
     * @param PrintJob $printJob
     *
     * @return bool
     */
    public function view(mixed $user, PrintJob $printJob)
    {
        if($user instanceof PrintServer)
            return $printJob->Printer->Server->is($user);

        if($user instanceof ClientApplication)
            return optional($printJob->ClientApplication)->is($user);
    }

    /**
     * Determine whether the user can create print jobs.
     *
     * @param mixed        $user
     *
     * @return bool
     */
    public function create(mixed $user)
    {
        if($user instanceof ClientApplication)
            return true;
    }

    /**
     * Determine whether the user can update the print job.
     *
     * @param mixed     $user
     * @param PrintJob $printJob
     *
     * @return bool
     */
    public function update(mixed $user, PrintJob $printJob)
    {
        if($user instanceof PrintServer)
            return $printJob->Printer->Server->is($user);
    }

    /**
     * Determine whether the user can delete the print job.
     *
     * @param mixed     $user
     * @param PrintJob $printJob
     *
     * @return bool
     */
    public function delete(mixed $user, PrintJob $printJob)
    {
        //
    }

    /**
     * Determine whether the user can restore the print job.
     *
     * @param mixed     $user
     * @param PrintJob $printJob
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
     * @param mixed     $user
     * @param PrintJob $printJob
     *
     * @return bool
     */
    public function forceDelete(mixed $user, PrintJob $printJob)
    {
        //
    }
}
