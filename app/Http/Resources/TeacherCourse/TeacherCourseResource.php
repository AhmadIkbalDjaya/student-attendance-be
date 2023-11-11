<?php

namespace App\Http\Resources\TeacherCourse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $levels = ["10", "11", "12"];
        return [
            "major" => $this->name,
            "levels" => LevelResource::collection($levels),
        ];
    }
}
