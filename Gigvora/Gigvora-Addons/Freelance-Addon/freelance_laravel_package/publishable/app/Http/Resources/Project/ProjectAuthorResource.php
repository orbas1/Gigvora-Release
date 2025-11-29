<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectAuthorResource extends JsonResource
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
            "first_name"            => $this->first_name,
            "last_name"             => $this->last_name,
            'full_name'             => $this->full_name,
            "image"                 => asset('storage/'.$this->whenHas('image')),
        ];
    }
}
