<?php

namespace App\Providers;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobPromise;
use App\Models\PrintServer;
use App\Models\Team;
use App\Policies\FortifySettingsPolicy;
use App\Policies\GeneralSettingsPolicy;
use App\Policies\PrintersPolicy;
use App\Policies\PrintJobPromisesPolicy;
use App\Policies\PrintJobsPolicy;
use App\Policies\PrintServerPolicy;
use App\Policies\TeamPolicy;
use App\Settings\FortifySettings;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        PrintJob::class => PrintJobsPolicy::class,
        Printer::class => PrintersPolicy::class,
        PrintServer::class => PrintServerPolicy::class,
        PrintJobPromise::class => PrintJobPromisesPolicy::class,
        Team::class => TeamPolicy::class,
        GeneralSettings::class => GeneralSettingsPolicy::class,
        FortifySettings::class => FortifySettingsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
