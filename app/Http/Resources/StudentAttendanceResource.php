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
        $student_attendance_id = StudentAttendance::selectRaw('id')->where("attendance_id", $this->pivot->attendance_id)->where('student_id', $this->id)->value('id');
        return [
            "id" => $student_attendance_id,
            "student_name" => $this->name,
            "nis" => $this->nis,
            "gender" => $this->gender,
            "status_id" => $this->pivot->status_id,
        ];
    }
}
