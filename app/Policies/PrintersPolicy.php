<?php

namespace App\Policies;

use App\Models\ClientApplication;
use App\Models\Printer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Arr;

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
     * @param mixed $user
     *
     * @return bool
     */
    public function viewAny(mixed $user)
    {
        if($user instanceof ClientApplication)
            return true;
    }

    /**
     * Determine whether the user can view the printer.
     *
     * @param mixed    $user
     * @param Printer $printer
     *
     * @return bool
     */
    public function view(mixed $user, Printer $printer)
    {
        if($user instanceof ClientApplication)
            return $user->Printers->contains($printer);
    }

    /**
     * @param mixed    $user
     * @param Printer $printer
     * @param string   $field
     *
     * @return bool
     * @throws \Exception
     */
    public function viewField(mixed $user, Printer $printer, string $field)
    {
        // fields = 'server', 'raw_languages_supported', 'ppd', 'uri', 'timestamps', 'location'

        if($user instanceof ClientApplication)
            return $this->view($user, $printer)
                && in_array($field, ['server', 'raw_languages_supported', 'ppd', 'location']);
    }

    /**
     * Determine whether the user can create printers.
     *
     * @param mixed $user
     *
     * @return bool
     */
    public function create(mixed $user)
    {
        //
    }

    /**
     * Determine whether the user can update the printer.
     *
     * @param mixed    $user
     * @param Printer $printer
     *
     * @return bool
     */
    public function update(mixed $user, Printer $printer)
    {
        //
    }

    /**
     * Determine whether the user can delete the printer.
     *
     * @param mixed    $user
     * @param Printer $printer
     *
     * @return bool
     */
    public function delete(mixed $user, Printer $printer)
    {
        //
    }

    /**
     * Determine whether the user can restore the printer.
     *
     * @param mixed    $user
     * @param Printer $printer
     *
     * @return bool
     */
    public function restore(mixed $user, Printer $printer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the printer.
     *
     * @param mixed    $user
     * @param Printer $printer
     *
     * @return bool
     */
    public function forceDelete(mixed $user, Printer $printer)
    {
        //
    }
}
