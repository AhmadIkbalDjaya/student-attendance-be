<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRecapController;
use App\Http\Controllers\Admin\ClaassController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\RecapController;
use App\Http\Controllers\Teacher\TeacherHomeController;
use App\Http\Controllers\Teacher\TeacherProfilController;
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
    Route::get('home', [AdminController::class, 'home']);
    Route::resource('semester', SemesterController::class);
    Route::get('semester/{semester}/setActive', [SemesterController::class, 'setActive']);
    Route::resource('claass', ClaassController::class)->except(["edit", "create"]);
    Route::resource('teacher', TeacherController::class)->except(["edit", "create"]);
    Route::resource('student', StudentController::class)->except(["edit", "create"]);
    Route::resource('course', CourseController::class)->except(["edit", "create"]);
    Route::controller(AdminRecapController::class)->group(function () {
        Route::get('/recap', 'index');
    });
});
Route::get('allMajor', [MajorController::class, 'allMajor']);

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::controller(AuthenticateController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware(['auth:sanctum']);
});

Route::prefix("teacher")->group(function () {
    Route::get("home", [TeacherHomeController::class, "index"])->middleware(['auth:sanctum']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::controller(TeacherProfilController::class)->group(function () {
            Route::get('teacherCourses', 'teacherCourses');
            Route::post('updateProfil', 'updateProfil');
            Route::post('changePass', 'changePass');
        });
    });
    Route::controller(AttendanceController::class)->group(function () {
        Route::get('attendance/list/{course}', 'attendanceList');
        Route::post('attendance/create/{course}', 'createAttendance');
        Route::get('attendance/{attendance}', 'showAttendance');
        Route::post('attendance/update/{attendance}', 'updateAttendance');
        Route::delete('attendance/{attendance}', 'destroyAttendance');
    });
});
Route::controller(RecapController::class)->group(function () {
    Route::get('recap/{course}', 'recap');
});
