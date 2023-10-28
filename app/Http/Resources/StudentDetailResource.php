<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            "id" => $this->id,
            "nis" => $this->nis,
            "name" => $this->name,
            "gender" => $this->gender,
            "gender" => $this->gender,
            "claass_id" => $this->claass_id,
            "claass_name" => $this->claass->name,
        ];
    }
}
