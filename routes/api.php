<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')
    ->group(function () {
        Route::prefix('/print-service')
            ->name('print-service.')
            ->group(function () {
                Route::middleware(['signed'])
                    ->group(function () {
                        Route::name('jobs')->apiResource(
                            '/jobs/{job}/content',
                            \App\Http\Controllers\PrintServiceApi\PrintJobContentController::class,
                            [
                                'only' => ['index'],
                            ],
                        );
                    }); // Route::middleware(['signed'])

                Route::middleware(['auth:print_service_api'])
                    ->group(function () {
                        Route::apiResource(
                            '/jobs',
                            \App\Http\Controllers\PrintServiceApi\PrintJobsController::class,
                            [
                                'only' => ['update', 'show', 'index'],
                            ],
                        );
                    }); // Route::middleware(['auth:print_service_api'])
            }); // Route::prefix('/print-service')->name('print-service.')

        Route::prefix('/web-print')
            ->name('web-print.')
            ->group(function () {
                Route::middleware(['auth:web_print_api'])
                    ->group(function () {
                        Route::apiResource(
                            '/printers',
                            \App\Http\Controllers\WebPrintApi\PrintersController::class,
                            [
                                'only' => ['show', 'index'],
                            ],
                        );

                        Route::name('promises')->apiResource(
                            '/promises/{promise}/content',
                            \App\Http\Controllers\WebPrintApi\PrintJobPromisesContentController::class,
                            [
                                'only' => ['index', 'store'],
                            ],
                        );

                        Route::apiResource(
                            '/promises',
                            \App\Http\Controllers\WebPrintApi\PrintJobPromisesController::class,
                            [
                                'only' => ['destroy', 'update', 'show', 'store', 'index'],
                            ],
                        );

                        Route::apiResource(
                            '/promises.dialog',
                            \App\Http\Controllers\WebPrintApi\PrintDialogsController::class,
                            [
                                'only' => ['store', 'index'],
                            ],
                        )->shallow();

                        Route::apiResource(
                            '/jobs',
                            \App\Http\Controllers\WebPrintApi\PrintJobsController::class,
                            [
                                'only' => ['store'],
                            ],
                        );
                    }); // Route::middleware(['auth:web_print_api'])
            }); // Route::middleware(['auth:web_print_api'])->prefix('/web-print')->name('web-print.')
    }); // Route::name('api.')
