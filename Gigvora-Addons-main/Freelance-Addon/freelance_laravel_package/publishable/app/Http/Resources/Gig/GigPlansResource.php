<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GigPlansResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "id"            => $this->id,
            "gig_id"        => $this->gig_id,
            "title"         => $this->title,
            "description"   => $this->description,
            "price"         => $this->price,
            "delivery_time" => $this->delivery_time,
            "is_featured"   => $this->is_featured,
            "options"       => $this->options,
        ];
    }
}
