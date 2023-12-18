<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseDetailResource;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;

class RecapController extends Controller
{
    public function recap(Course $course)
    {
        $students = Student::where('claass_id', $course->claass->id)->get();
        $attendances_id = Attendance::where("course_id", $course->id)->pluck("id");
        $student_attendances = StudentAttendance::whereIn("attendance_id", $attendances_id)->get();

        $response = [];
        foreach ($students as $student) {
            $h_count = 0;
            $s_count = 0;
            $i_count = 0;
            $a_count = 0;
            foreach ($student_attendances as $sa) {
                if ($student->id == $sa->student_id) {
                    $h_count = $sa->status_id == 1 ? $h_count + 1 : $h_count;
                    $s_count = $sa->status_id == 2 ? $s_count + 1 : $s_count;
                    $i_count = $sa->status_id == 3 ? $i_count + 1 : $i_count;
                    $a_count = $sa->status_id == 4 ? $a_count + 1 : $a_count;
                }
            }
            $response[] = [
                "name" => $student->name,
                "nis" => $student->nis,
                "gender" => $student->gender,
                "h_count" => $h_count,
                "s_count" => $s_count,
                "i_count" => $i_count,
                "a_count" => $a_count,
            ];
        }

        $data = [
            "course" => new CourseDetailResource($course),
            "students_recap" => $response,

        ];
        return response()->json(["data" => $data], 200);
    }
}
