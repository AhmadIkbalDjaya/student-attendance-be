<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResources extends JsonResource
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
            "start_year" => $this->start_year,
            "end_year" => $this->end_year,
            "odd_even" => $this->odd_even,
            "is_active" => $this->is_active,
        ];
    }
}
