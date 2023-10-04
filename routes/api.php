<?php

use App\Http\Controllers\Admin\SemesterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix("admin")->group(function () {
    Route::controller(SemesterController::class)->group(function () {
        Route::get('semester', 'index');
        Route::get('semester/{semester}', 'show');
        Route::post('semester', 'store');
        Route::get('semester/{semester}/setActive', 'setActive');
        Route::delete('semester/{semester}', 'destroy');
    });
});
