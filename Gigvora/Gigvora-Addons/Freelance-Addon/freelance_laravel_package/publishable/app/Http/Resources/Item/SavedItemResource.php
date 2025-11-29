<?php

namespace App\Http\Resources\Item;

use App\Http\Resources\Gig\GigResource;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Project\ProjectResouce;
use App\Http\Resources\Seller\SellerResouce;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return match ($this->type) {
            'gig'       => new GigResource($this->gigs, ['is_favourite' => 1]),
            'profile'   => new SellerResouce($this->sellers,  ['is_favourite' => 1]),
            'project'   => new ProjectResouce($this->projects,  ['is_favourite' => 1]),
            default     => null,
        };
    }
}
