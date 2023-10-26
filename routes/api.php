<?php

use App\Http\Controllers\Admin\ClaassController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\RecapController;
use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
    Route::resource('claass', ClaassController::class)->except(["edit", "create"]);
    Route::resource('teacher', TeacherController::class)->except(["edit", "create"]);
    Route::resource('student', StudentController::class)->except(["edit", "create"]);
    Route::resource('course', CourseController::class)->except(["edit", "create"]);
});
Route::get('allMajor', [MajorController::class, 'allMajor']);

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::controller(AuthenticateController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware(['auth:sanctum']);
    Route::get('teacherCourses', 'teacherCourses')->middleware(['auth:sanctum']);
});

Route::prefix("teacher")->group(function () {
    Route::controller(AttendanceController::class)->group(function () {
        Route::get('attendance/list/{course}', 'attendanceList');
        Route::post('attendance/create/{course}', 'createAttendance');
        Route::get('attendance/{attendance}', 'showAttendance');
        Route::post('attendance/update/{attendance}', 'updateAttendance');
        Route::delete('attendance/{attendance}', 'destroyAttendance');
    });
    Route::controller(RecapController::class)->group(function () {
        Route::get('recap/{course}', 'recap');
    });
});
