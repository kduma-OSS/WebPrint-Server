<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['signed'])
    ->prefix('/api/web-print')
    ->name('api.web-print.')
    ->group(function () {
        Route::name('print-dialog')->get(
            '/print-dialog/{dialog}',
            \App\Http\Controllers\WebPrintApi\UserPrintDialogController::class,
        );
    }); // Route::middleware(['signed'])->prefix('/api/web-print')->name('api.web-print.')

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/app/settings', \App\Http\Controllers\App\SettingsController::class)
        ->name('app.settings');

    Route::prefix('web-print')
        ->name('web-print.')
        ->group(function () {
            Route::resource('servers', \App\Http\Controllers\App\PrintServersController::class);
            Route::resource('servers.printers', \App\Http\Controllers\App\PrintServerPrintersController::class);
            Route::resource('promises', \App\Http\Controllers\App\PrintJobPromisesController::class);
            Route::resource('jobs', \App\Http\Controllers\App\PrintJobsController::class);
            Route::resource('apps', \App\Http\Controllers\App\ClientApplicationsController::class);
        });
});
