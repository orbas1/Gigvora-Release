<?php

namespace App\Http\Resources\Education;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ 
            "id"                    => $this->id,
            "profile_id"            => $this->profile_id,
            "deg_title"             => $this->deg_title,
            "deg_institue_name"     => $this->deg_institue_name,
            "deg_description"       => $this->deg_description,
            "address"               => $this->address,
            "deg_start_date"        => date('F d, Y', strtotime($this->deg_start_date)),
            "deg_end_date"          => date('F d, Y', strtotime($this->deg_end_date)),
            "is_ongoing"            => $this->is_ongoing,
        ];
    }
}
