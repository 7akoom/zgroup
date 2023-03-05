<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesManController;
use App\Http\Controllers\CustomerController;

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

Route::controller(SalesmanController::class)->group(function () {
    Route::prefix('salesman')->group(function () {
        Route::get('/', 'index');
        Route::post('/create', 'store');
        Route::post('/update/{id}', 'update');
        Route::post('/delete/{id}', 'destroy');
        Route::post('/import','import');
        Route::post('/export','export');
    });
});

Route::controller(CustomerController::class)->group(function () {
    Route::prefix('customer')->group(function () {
        Route::post('/', 'index');
        Route::post('/create', 'store');
        Route::post('/update/{id}', 'update');
        Route::post('/delete/{id}', 'destroy');
        Route::post('/import','import');
        Route::post('/export','export');
    });
});
