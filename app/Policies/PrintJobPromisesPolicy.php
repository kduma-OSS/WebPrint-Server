<?php

namespace App\Policies;

use App\Models\ClientApplication;
use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\PrintJobPromise;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrintJobPromisesPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any print job promises.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function viewAny(mixed $user)
    {
        if ($user instanceof ClientApplication) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the print job promise.
     *
     * @param  mixed  $user
     * @param  PrintJobPromise  $printJobPromise
     * @return bool
     */
    public function view(mixed $user, PrintJobPromise $printJobPromise)
    {
        if ($user instanceof ClientApplication) {
            return $printJobPromise->ClientApplication?->is($user);
        }
    }

    /**
     * @param  mixed  $user
     * @param  PrintJobPromise  $printJobPromise
     * @param  string  $field
     * @return bool
     *
     * @throws \Exception
     */
    public function viewField(mixed $user, PrintJobPromise $printJobPromise, string $field)
    {
        // fields = 'timestamps'

        if ($user instanceof ClientApplication) {
            return $this->view($user, $printJobPromise)
                && in_array($field, ['timestamps']);
        }
    }

    /**
     * Determine whether the user can create print job promises.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function create(mixed $user)
    {
        if ($user instanceof ClientApplication) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the print job promise.
     *
     * @param  mixed  $user
     * @param  PrintJobPromise  $printJobPromise
     * @return bool
     */
    public function update(mixed $user, PrintJobPromise $printJobPromise)
    {
        if ($user instanceof ClientApplication) {
            return $printJobPromise->ClientApplication?->is($user) && in_array($printJobPromise->status, [
                PrintJobPromiseStatusEnum::Draft,
                PrintJobPromiseStatusEnum::New,
                PrintJobPromiseStatusEnum::Ready,
            ]);
        }
    }

    /**
     * Determine whether the user can delete the print job promise.
     *
     * @param  mixed  $user
     * @param  PrintJobPromise  $printJobPromise
     * @return bool
     */
    public function delete(mixed $user, PrintJobPromise $printJobPromise)
    {
        if ($user instanceof ClientApplication) {
            return $printJobPromise->ClientApplication?->is($user) && in_array($printJobPromise->status, [
                PrintJobPromiseStatusEnum::Draft,
                PrintJobPromiseStatusEnum::New,
                PrintJobPromiseStatusEnum::Ready,
            ]);
        }
    }

    /**
     * Determine whether the user can restore the print job promise.
     *
     * @param  mixed  $user
     * @param  PrintJobPromise  $printJobPromise
     * @return bool
     */
    public function restore(mixed $user, PrintJobPromise $printJobPromise)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the print job promise.
     *
     * @param  mixed  $user
     * @param  PrintJobPromise  $printJobPromise
     * @return bool
     */
    public function forceDelete(mixed $user, PrintJobPromise $printJobPromise)
    {
        //
    }
}
