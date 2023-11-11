<?php

namespace App\Http\Resources\TeacherCourse;

use App\Models\Claass;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $activeSemesterId = Semester::where('is_active', true)->pluck('id')->first();
        $claasses = Claass::whereHas("courses", function ($query) use ($activeSemesterId) {
            $query->where("teacher_id", Auth::user()->teacher->id)
                ->where('semester_id', $activeSemesterId);
        })->with(['courses' => function ($query) use ($activeSemesterId) {
            $query->where("teacher_id", Auth::user()->teacher->id)
                ->where('semester_id', $activeSemesterId);
        }])->where('major_id', $this["major_id"])
            ->where('level', $this['level'])
            ->get();
        return [
            // "level" => $this->resource,
            "level" => $this["level"],
            "claasses" => TeacherClaassResource::collection($claasses),
        ];
    }
}
