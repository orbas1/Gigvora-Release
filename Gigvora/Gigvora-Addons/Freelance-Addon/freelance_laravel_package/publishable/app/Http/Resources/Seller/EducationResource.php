<?php

namespace App\Http\Resources\Seller;

use App\Http\Resources\Media\MediaResource;
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
            'id'                => $this->id,
            'deg_title'         => $this->deg_title,
            'deg_institue_name' => $this->deg_institue_name,
            'deg_description'   => $this->deg_description,
            'deg_start_date'    => $this->deg_start_date,
            'deg_end_date'      => $this->deg_end_date,
        ];
    }
}
