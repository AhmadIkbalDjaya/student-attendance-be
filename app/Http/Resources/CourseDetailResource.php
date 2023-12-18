<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $semester = $this->semester;
        $odd_even = $semester->odd_even ? "Ganjil" : "Genap";
        return [
            "id" => $this->id,
            "name" => $this->name,
            "claass" => $this->claass->name,
            "claass_id" => $this->claass_id,
            "teacher" => $this->teacher->name,
            "teacher_id" => $this->teacher_id,
            "semester" => "($odd_even) $semester->start_year / $semester->end_year",
            "semester_id" => $this->semester_id,
            "student_count" => $this->students->count(),
            "attendance_count" => $this->attendances->count(),
        ];
    }
}
