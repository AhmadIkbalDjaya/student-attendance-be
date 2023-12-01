<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\StudentAttendanceResource;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function courseAttendances(Course $course)
    {
        $semester = $course->semester->odd_even ? "Ganjil" : "Genap";
        return response()->json([
            "data" => [
                "course" => [
                    "id" => $course->id,
                    "name" => $course->name,
                    "claass" => $course->claass->name,
                    "semester" => "(" . $semester . ") " . $course->semester->start_year . " / "  . $course->semester->end_year,
                ],
                "attendances" => AttendanceResource::collection($course->attendances),
            ]
        ], 200);
    }

    public function show(Attendance $attendance)
    {
        return response()->json([
            "data" => [
                "attendance" => new AttendanceResource($attendance),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "course_id" => "required|exists:courses,id",
            "title" => "required|string",
            "datetime" => "required|date",
        ]);
        $course = Course::find($validated["course_id"]);
        $validated["course_id"] = $course->id;
        try {
            $attendance = null;
            DB::transaction(function () use ($validated, $course, &$attendance) {
                $students_id = Student::where("claass_id", $course->claass_id)->pluck("id");
                $attendance = Attendance::create($validated);
                foreach ($students_id as $student) {
                    StudentAttendance::create([
                        "attendance_id" => $attendance->id,
                        "student_id" => $student,
                        "status_id" => 5,
                    ]);
                }
            });
            return response()->json([
                "message" => "Absesi berhasil di buat",
                "data" => new AttendanceResource($attendance),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Terjadi Kesalahan Pada Saat Menambahkan",
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    public function update(Attendance $attendance, Request $request)
    {
        $validated = $request->validate([
            "title" => "required|string",
            "datetime" => "required|date",
        ]);
        try {
            $attendance->update($validated);
            return response()->json([
                "data" => new AttendanceResource($attendance),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Kehadiran gagal di edit",
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Attendance $attendance)
    {
        try {
            $attendance->delete();
            return response()->json([
                "success" => true,
                "message" => "Absensi Berhasil di Hapus",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Absensi Gagal di Hapus",
                "error" => $th->getMessage(),
            ], 500);
        }
    }
}
