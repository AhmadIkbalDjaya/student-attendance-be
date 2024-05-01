<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRecapController;
use App\Http\Controllers\Admin\ClaassController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\RecapController;
use App\Http\Controllers\Teacher\StudentAttendanceController;
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
    Route::middleware(["auth:sanctum", 'role:0'])->group(function () {
        Route::get('home', [AdminController::class, 'home']);
        Route::resource('semester', SemesterController::class)->except(["edit", "create"]);
        Route::get('semester/{semester}/setActive', [SemesterController::class, 'setActive']);
        Route::resource('claass', ClaassController::class)->except(["edit", "create"]);
        Route::resource('teacher', TeacherController::class)->except(["edit", "create"]);
        Route::post('teacher/setPass/{teacher}', [TeacherController::class, 'setPass']);
        Route::resource('student', StudentController::class)->except(["edit", "create"]);
        Route::get('studentByClaass/{claass}', [StudentController::class, 'studentByClaass']);
        Route::resource('course', CourseController::class)->except(["edit", "create"]);
        Route::get('recap', [AdminRecapController::class, 'index']);
        Route::resource('aboutUs', AboutUsController::class)->except(['edit', 'create'])->parameters(["aboutUs" => "aboutUs"]);
        Route::get('major', [MajorController::class, 'index']);
    });
});

Route::middleware(["auth:sanctum"])->group(function () {
    Route::get('admin/aboutUs', [AboutUsController::class, 'index']);
    Route::controller(RecapController::class)->group(function () {
        Route::get('recap/{course}', 'recap');
    });
});

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::controller(AuthenticateController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware(['auth:sanctum']);
    Route::post('user/changePass', 'changePass')->middleware(['auth:sanctum']);
});

Route::prefix("teacher")->group(function () {
    Route::middleware(['auth:sanctum', 'role:1'])->group(function () {
        Route::get("home", [TeacherHomeController::class, "index"]);
        Route::controller(TeacherProfilController::class)->group(function () {
            Route::get('teacherCourses', 'teacherCourses');
            Route::post('updateProfil', 'updateProfil');
        });
        Route::controller(AttendanceController::class)->group(function () {
            Route::get('attendance/course/{course}', 'courseAttendances');
            Route::get('attendance/{attendance}', 'show');
            Route::post('attendance', 'store');
            Route::put('attendance/{attendance}', 'update');
            Route::delete('attendance/{attendance}', 'destroy');
        });
        Route::controller(StudentAttendanceController::class)->group(function () {
            Route::get('studentAttendance/{attendance}', 'show');
            Route::put('studentAttendance/{attendance}', 'update');
        });
    });
});
