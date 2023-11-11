<?php

namespace App\Http\Resources\TeacherCourse;

use App\Models\Claass;
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
        $claasses = Claass::whereHas("courses", function ($query) {
            $query->where("teacher_id", Auth::user()->teacher->id);
        })->with(['courses' => function ($query) {
            $query->where("teacher_id", Auth::user()->teacher->id);
        }])->get();
        return [
            "level" => $this->resource,
            "claasses" => TeacherClaassResource::collection($claasses),
        ];
    }
}
