<?php

namespace App\Providers;

use App\Models\ClientApplication;
use App\Models\PrintServer;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'server' => PrintServer::class,
            'client' => ClientApplication::class,
            'user' => User::class,
        ]);

        Str::macro('linesCount', function (string $string): int {
            return substr_count($string, "\n") + 1;
        });
    }
}
