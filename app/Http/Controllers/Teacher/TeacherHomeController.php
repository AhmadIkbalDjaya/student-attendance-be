<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Claass;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherHomeController extends Controller
{
    public function index()
    {
        $active_semester_id = Semester::where("is_active", 1)->pluck('id')->first();
        $active_semester_id = $active_semester_id == null ?  0 : $active_semester_id;
        $course_count = Course::where("teacher_id", auth()->user()->teacher->id)
            ->where("semester_id", $active_semester_id)
            ->count();
        $claass_count = Claass::whereHas("courses", function ($q) use ($active_semester_id) {
            $q->where("teacher_id", auth()->user()->teacher->id)->where("semester_id", $active_semester_id);
        })->count();
        $student_count = Student::whereHas("claass", function ($q) use ($active_semester_id) {
            $q->whereHas("courses", function ($q) use ($active_semester_id) {
                $q->where("teacher_id", auth()->user()->teacher->id)->where("semester_id", $active_semester_id);
            });
        })->count();
        $attendance_count = Attendance::whereHas("course", function ($q) use ($active_semester_id) {
            $q->where("teacher_id", auth()->user()->teacher->id)->where("semester_id", $active_semester_id);
        })->count();
        $attendance_null_count = Attendance::whereHas("course", function ($q) use ($active_semester_id) {
            $q->where("teacher_id", auth()->user()->teacher->id)->where("semester_id", $active_semester_id);
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
