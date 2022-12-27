<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return mixed
     */
    public function view(User $user, Team $team): bool
    {
        return $user->belongsToTeam($team) && ! $team->personal_team;
    }

    public function viewDashboard(User $user, Team $team, string $widget): bool
    {
        if(!$user->belongsToTeam($team))
            return false;

        if($team->personal_team) {
            return in_array($widget, [
                'teams',
            ]);
        }

        return in_array($widget, [
            'stats',
            'servers',
        ]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return mixed
     */
    public function update(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) && ! $team->personal_team;
    }

    /**
     * Determine whether the user can add team members.
     *
     * @return mixed
     */
    public function addTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) && ! $team->personal_team;
    }

    /**
     * Determine whether the user can update team member permissions.
     *
     * @return mixed
     */
    public function updateTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) && ! $team->personal_team;
    }

    /**
     * Determine whether the user can remove team members.
     *
     * @return mixed
     */
    public function removeTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) && ! $team->personal_team;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) && ! $team->personal_team;
    }
}
