<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentAttendanceResource;
use App\Models\Attendance;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAttendanceController extends Controller
{
    public function show(Attendance $attendance)
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

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            "ids" => "required|array",
            "ids.*" => "exists:student_attendances,id",
            "status_ids" => "required|array|size:" . count($request->ids),
            "status_ids.*" => "exists:attendance_statuses,id",
            "images" => "required|array|size:" . count($request->ids),
            // "images.*" => "nullable|image|mimes:jpeg,png,jpg|max:2048",
            // "images.*" => "nullable|string",
        ]);
        foreach ($request->images as $index => $image) {
            if (gettype($image) == "object") {
                $validated["images"][$index] = $image->storePublicly("student_attendance", "public");
            } else {
                $validated["images"][$index] = null;
            }
        }
        try {
            DB::transaction(function () use ($attendance, $validated) {
                foreach ($validated['ids'] as $key => $student_attendance) {
                    StudentAttendance::where('id', $student_attendance)->update([
                        "status_id" => $validated['status_ids'][$key],
                        "image" => $validated['images'][$key],
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
}
