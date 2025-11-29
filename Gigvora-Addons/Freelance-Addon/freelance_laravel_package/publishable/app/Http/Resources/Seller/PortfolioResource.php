<?php

namespace App\Http\Resources\Seller;

use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'url'           => $this->url,
            'attachments'   => !empty($this->attachments) ? new MediaResource($this->attachments): null,
            'description'   => $this->description,
        ];
    }
}
