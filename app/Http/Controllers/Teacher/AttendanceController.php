<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
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
    public function attendanceList(Course $course)
    {
        $attendances = [];
        foreach ($course->attendances as $attendance) {
            $attendances[] = [
                "id" => $attendance->id,
                "title" => $attendance->title,
                "is_filled" => $attendance->is_filled,
            ];
        }
        $semester = $course->semester->odd_even ? "Ganjil" : "Genap";
        return response()->json([
            "data" => [
                "course" => [
                    "id" => $course->id,
                    "name" => $course->name,
                    "claass" => $course->claass->name,
                    "semester" => "(" . $semester . ") " . $course->semester->start_year . " / "  . $course->semester->end_year,
                ],
                "attendances" => $attendances,
            ]
        ], 200);
    }

    public function createAttendance(Request $request, Course $course)
    {
        $validated = $request->validate([
            "title" => "required|string",
            "datetime" => "required|date",
        ]);
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
                "data" => $attendance,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Terjadi Kesalahan Pada Saat Menambahkan",
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    public function showAttendance(Attendance $attendance)
    {
        $data = [
            "attendance" => [
                "id" => $attendance->id,
                "title" => $attendance->title,
                "claass" => $attendance->course->claass->name,
                "course" => $attendance->course->name,
                "datetime" => Carbon::parse($attendance->datetime)->isoFormat("DD MMMM YYYY - HH:mm"),
                "student_count" => $attendance->course->claass->students->count(),
            ],
            "student_attendances" => StudentAttendanceResource::collection($attendance->students),
        ];
        return response()->json(["data" => $data], 200);
    }

    public function updateAttendance(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            "ids" => "required|array",
            "ids.*" => "exists:student_attendances,id",
            "status_ids" => "required|array|size:" . count($request->ids),
            "status_ids.*" => "exists:attendance_statuses,id",
        ]);
        try {
            DB::transaction(function () use ($attendance, $validated) {
                foreach ($validated['ids'] as $key => $student_attendance) {
                    StudentAttendance::where('id', $student_attendance)->update([
                        "status_id" => $validated['status_ids'][$key]
                    ]);
                }
                $attendance->update([
                    "is_filled" => 1,
                ]);
            });
            return response()->json([
                "message" => "Kehadiran Berhasil Disimpan",
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Kehadiran gagal di simpan",
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    public function destroyAttendance(Attendance $attendance)
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
