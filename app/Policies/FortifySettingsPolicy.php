<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FortifySettingsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->is_system_admin;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User $user
     * @param  string           $setting
     * @return mixed
     */
    public function view(User $user, string $setting)
    {
        return $user->is_system_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @param  string           $setting
     * @return mixed
     */
    public function update(User $user, string $setting)
    {
        return $user->is_system_admin;
    }
}
