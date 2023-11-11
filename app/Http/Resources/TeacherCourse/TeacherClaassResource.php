<?php

namespace App\Http\Resources\TeacherCourse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherClaassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "claass_name" => $this->name,
            "courses" => TeacherCoursesResource::collection($this->courses),
        ];
    }
}
