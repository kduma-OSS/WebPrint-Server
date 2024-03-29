<?php

namespace App\Policies;

use App\Models\ClientApplication;
use App\Models\Enums\PrintJobPromiseStatusEnum;
use App\Models\PrintJobPromise;
use App\Models\Team;
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
     * @return bool
     */
    public function viewAny(mixed $user, Team $team = null)
    {
        if ($user instanceof ClientApplication) {
            return true;
        }

        if ($user instanceof User) {
            $team ??= $user->currentTeam;

            return ! $team->personal_team
                && $user->hasTeamPermission($team, 'promise:read')
                && $user->tokenCan('promise:read');
        }
    }

    /**
     * Determine whether the user can view the print job promise.
     *
     * @return bool
     */
    public function view(mixed $user, PrintJobPromise $printJobPromise)
    {
        if ($user instanceof ClientApplication) {
            return $printJobPromise->ClientApplication?->is($user);
        }

        if ($user instanceof User) {
            return $user->belongsToTeam($printJobPromise->ClientApplication->Team)
                && $user->hasTeamPermission($printJobPromise->ClientApplication->Team, 'promise:read')
                && $user->tokenCan('promise:read');
        }
    }

    /**
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

        if ($user instanceof User) {
            return ! $user->currentTeam->personal_team;
        }
    }

    /**
     * Determine whether the user can create print job promises.
     *
     * @return bool
     */
    public function create(mixed $user, Team $team = null)
    {
        if ($user instanceof ClientApplication) {
            return true;
        }

        if ($user instanceof User) {
            $team ??= $user->currentTeam;

            return ! $team->personal_team
                && $user->hasTeamPermission($team, 'promise:read')
                && $user->tokenCan('promise:read');
        }
    }

    /**
     * Determine whether the user can update the print job promise.
     *
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

        if ($user instanceof User) {
            return $user->belongsToTeam($printJobPromise->ClientApplication->Team)
                && $user->hasTeamPermission($printJobPromise->ClientApplication->Team, 'promise:update')
                && $user->tokenCan('promise:update')
                && in_array($printJobPromise->status, [
                    PrintJobPromiseStatusEnum::Draft,
                    PrintJobPromiseStatusEnum::New,
                    PrintJobPromiseStatusEnum::Ready,
                ]);
        }
    }

    /**
     * Determine whether the user can delete the print job promise.
     *
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

        if ($user instanceof User) {
            return $user->belongsToTeam($printJobPromise->ClientApplication->Team)
                && $user->hasTeamPermission($printJobPromise->ClientApplication->Team, 'promise:delete')
                && $user->tokenCan('promise:delete')
                && in_array($printJobPromise->status, [
                    PrintJobPromiseStatusEnum::Draft,
                    PrintJobPromiseStatusEnum::New,
                    PrintJobPromiseStatusEnum::Ready,
                ]);
        }
    }

    /**
     * Determine whether the user can restore the print job promise.
     *
     * @return bool
     */
    public function restore(mixed $user, PrintJobPromise $printJobPromise): void
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the print job promise.
     *
     * @return bool
     */
    public function forceDelete(mixed $user, PrintJobPromise $printJobPromise): void
    {
        //
    }
}
