<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claass;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function home()
    {
        $claass_count = Claass::count();
        $teacher_count = Teacher::count();
        $student_count = Student::count();
        $course_count = Course::count();
        return response()->json([
            "data" => [
                "claass_count" => $claass_count,
                "teacher_count" => $teacher_count,
                "student_count" => $student_count,
                "course_count" => $course_count,
            ],
        ], 200);
    }
}
