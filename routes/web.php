<?php

use App\Http\Controllers\App\ClientApplicationsController;
use App\Http\Controllers\App\PrintJobPromisesController;
use App\Http\Controllers\App\PrintJobsController;
use App\Http\Controllers\App\PrintServerPrintersController;
use App\Http\Controllers\App\PrintServersController;
use App\Http\Controllers\App\SettingsController;
use App\Http\Controllers\WebPrintApi\UserPrintDialogController;
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

Route::redirect('/', '/dashboard');

Route::middleware(['signed'])
    ->prefix('/api/web-print')
    ->name('api.web-print.')
    ->group(function (): void {
        Route::name('print-dialog')->get(
            '/print-dialog/{dialog}',
            UserPrintDialogController::class,
        );
    }); // Route::middleware(['signed'])->prefix('/api/web-print')->name('api.web-print.')

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function (): void {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/app/settings', SettingsController::class)
        ->name('app.settings');

    Route::prefix('web-print')
        ->name('web-print.')
        ->group(function (): void {
            Route::resource('servers', PrintServersController::class)
                ->only(['index', 'create', 'store', 'show']); // update, destroy, edit

            Route::resource('servers.printers', PrintServerPrintersController::class)
                ->shallow()
                ->only(['index', 'create', 'store', 'show']); // update, destroy, edit

            Route::resource('promises', PrintJobPromisesController::class)
                ->only(['index']); // create, update, destroy, edit, store, show

            Route::resource('jobs', PrintJobsController::class)
                ->only(['index']); // create, update, destroy, edit, store, show

            Route::resource('apps', ClientApplicationsController::class)
                ->only(['index', 'create', 'store', 'show']); // update, destroy, edit
        });
});
