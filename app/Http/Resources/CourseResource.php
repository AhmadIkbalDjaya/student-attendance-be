<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            "name" => $this->name,
            "claass" => $this->claass->name,
            "claass_id" => $this->claass_id,
            "teacher" => $this->teacher->name,
            "teacher_id" => $this->teacher_id,
            "semester" => $this->semester->name,
            "semester_id" => $this->semester_id,
        ];
    }
}
