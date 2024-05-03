<?php

namespace App\Http\Resources;

use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $student_attendance = StudentAttendance::select(['id', 'image'])->where("attendance_id", $this->pivot->attendance_id)->where('student_id', $this->id)->first();
        return [
            "id" => $student_attendance->id,
            "student_name" => $this->name,
            "nis" => $this->nis,
            "gender" => $this->gender,
            "status_id" => $this->pivot->status_id,
            "image" => $student_attendance->image ? url("storage/$student_attendance->image") : null,
        ];
    }
}
