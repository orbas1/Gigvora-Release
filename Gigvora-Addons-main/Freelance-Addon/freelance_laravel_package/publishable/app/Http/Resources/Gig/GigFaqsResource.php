<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GigFaqsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "id"        => $this->id,
            "gig_id"    => $this->gig_id,
            "question"  => $this->question,
            "answer"    => cleanString($this->answer),
        ];
    }
}
