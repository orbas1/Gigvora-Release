<?php

namespace App\Http\Resources\Seller;

use App\Http\Resources\Gig\GigResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerDetailResource extends JsonResource
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
            'name'              => $this->short_name,
            'full_name'         => $this->full_name,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'description'       => $this->description,
            'image'             => $this->userImage(),
            'address'           => $this->when($this->address, getUserAddress($this->address, setting('_general.address_format') ?? 'state_country')),
            'tagline'           => $this->tagline,
            "skills"            => $this->skills?->pluck('name') ?? [],
            'languages'         => $this->languages?->pluck('name') ?? [],
            'education'         => EducationResource::collection($this->whenLoaded('education')),
            'english_level'     => $this->english_level,
            'seller_type'       => $this->seller_type,
            'gigs'              => GigResource::collection($this->whenLoaded('gigs')),
            'portfolio'         => PortfolioResource::collection($this->whenLoaded('portfolio')),
            'starting_from'     => $this->user->userAccountSetting->hourly_rate,
            'reviews'           => number_format($this->ratings->count()),
            'rating'            => ratingFormat($this->ratings_avg_rating),
            'views'             => number_format($this->profile_visits_count),
            'is_favourite'      => $this->is_favourite ?? 0
        ];
    }

    public function userImage()
    {
        $default_image = '/images/default-user-130x130.png';
        $image_path = !empty($this->image) ? getProfileImageURL($this->image, '130x130') : '';
        $path = !empty($image_path) ? '/storage/' . $image_path : $default_image;

        return asset($path);
    }
}
