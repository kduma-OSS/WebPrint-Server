<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::role('admin', 'Administrator', [
            'server:create', 'server:read', 'server:update', 'server:delete',
            'printer:create', 'printer:read', 'printer:update', 'printer:delete',
            'client:create', 'client:read', 'client:update', 'client:delete',
            /*'job:create',*/ 'job:read', /*'job:update', 'job:delete',*/
            /*'promise:create',*/ 'promise:read', /*'promise:update', 'promise:delete',*/
        ])->description('Administrator users can perform any action.');

        Jetstream::role('editor', 'Editor', [
            'server:create', 'server:read', 'server:update',
            'printer:create', 'printer:read', 'printer:update',
            'client:create', 'client:read', 'client:update',
            /*'job:create',*/ 'job:read', /*'job:update',*/
            /*'promise:create',*/ 'promise:read', /*'promise:update',*/
        ])->description('Editor users have the ability to read, create, and update.');

        Jetstream::role('viewer', 'Viewer', [
            'server:read',
            'printer:read',
            'client:read',
            'job:read',
            'promise:read',
        ])->description('Viewer users have the ability only to read.');

        // Jetstream::defaultApiTokenPermissions([]);

        // Jetstream::permissions([]);
    }
}
