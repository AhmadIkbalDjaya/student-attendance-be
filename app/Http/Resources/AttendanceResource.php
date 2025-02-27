<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            "title" => $this->title,
            "datetime" => $this->datetime,
            "course_id" => $this->course_id,
            "course" => $this->course->name,
            "is_filled" => $this->is_filled,
        ];
    }
}
