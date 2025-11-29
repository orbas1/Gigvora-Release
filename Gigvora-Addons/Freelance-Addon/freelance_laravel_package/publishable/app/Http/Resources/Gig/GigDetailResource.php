<?php

namespace App\Http\Resources\Gig;

use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GigDetailResource extends JsonResource
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
            "attachments"       => !empty($this->attachments['files']) ? [ 'video_url' => $this->attachments['video_url'], 'files' => new MediaResource($this->attachments['files']) ]: null,
            "title"             => $this->title,
            "auther"            => $this->gigAuthor?->short_name,
            "auther_full_name"  => $this->gigAuthor?->full_name,
            'user_avatar'       => asset('storage/'.$this->gigAuthor?->image),
            'description'       => cleanString($this->description),
            'faqs'              => GigFaqsResource::collection($this->whenLoaded('faqs')),
            'gig_plans'         => GigPlansResource::collection($this->whenLoaded('gig_plans')),
            'sales'             => $this->gig_orders_count,
            'rating'            => ratingFormat($this->ratings_avg_rating),
            'reviews'           => number_format($this->ratings->count()),
            'views'             => number_format($this->gig_visits_count),
            "address"           => $this->when($this->address, getUserAddress($this->address, setting('_general.address_format') ?? 'state_country')),
            'is_featured'       => $this->is_featured,
            'is_favourite'      => $this->is_favourite,
        ];
    }
}
