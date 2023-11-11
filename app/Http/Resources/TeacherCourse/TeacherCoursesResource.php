<?php

namespace App\Http\Resources\TeacherCourse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCoursesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "course_name"  => $this->name,
            "claass_id" => $this->claass_id,
            "teacher_id" => $this->teacher_id,
            "semester_id" => $this->semester_id,
        ];
    }
}
