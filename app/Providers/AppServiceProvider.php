<?php

namespace App\Providers;

use App\Models\ClientApplication;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'server' => PrintServer::class,
            'client' => ClientApplication::class,
            'user' => User::class,
        ]);
    }
}
