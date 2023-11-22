<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Claass;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherHomeController extends Controller
{
    public function index()
    {
        $course_count = Course::where("teacher_id", auth()->user()->teacher->id)->count();
        $claass_count = Claass::whereHas("courses", function ($q) {
            $q->where("teacher_id", auth()->user()->teacher->id);
        })->count();
        $student_count = Student::whereHas("claass", function ($q) {
            $q->whereHas("courses", function ($q) {
                $q->where("teacher_id", auth()->user()->teacher->id);
            });
        })->count();
        $attendance_count = Attendance::whereHas("course", function ($q) {
            $q->where("teacher_id", auth()->user()->teacher->id);
        })->count();
        $attendance_null_count = Attendance::whereHas("course", function ($q) {
            $q->where("teacher_id", auth()->user()->teacher->id);
        })->where("is_filled", false)->count();
        return response()->json([
            "data" => [
                "claass_count" => $claass_count,
                "course_count" => $course_count,
                "student_count" => $student_count,
                "attendance_count" => $attendance_count,
                "attendance_null_count" => $attendance_null_count,
            ],
        ], 200);
    }
}
