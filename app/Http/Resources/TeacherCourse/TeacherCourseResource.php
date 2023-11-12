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
        $levels = [
            [
                "level" => "10",
                "major_id" => $this->id,
            ],
            [
                "level" => "11",
                "major_id" => $this->id,
            ],
            [
                "level" => "12",
                "major_id" => $this->id,
            ],
        ];
        return [
            "major" => $this->name,
            "levels" => LevelResource::collection($levels),
        ];
    }
}
