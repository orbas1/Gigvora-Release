<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResouce extends JsonResource
{
    protected $data_values;
    
    public function __construct($resource, $data=null)
    {
        parent::__construct($resource);
        $this->data_values = is_array($data) ? $data : [];
        
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $record = [
            "id"                    => $this->id,
            "user_id"               => $this->user_id,
            "slug"                  => $this->slug,
            "first_name"            => $this->first_name,
            "last_name"             => $this->last_name,
            // "image"                 => !empty($this->image) ? asset('storage/'.$this->image) : asset('images/default-user.jpg'),
            "image"                 => $this->userImage(),
            "tagline"               => $this->tagline,
            "address"               => !empty($this->address) ? getUserAddress($this->address, setting('_general.address_format') ?? 'state_country') : '',
            "description"           => $this->description,
            "verification"          => $this->verification,
            "hourly_rate"           => $this->hourly_rate,
            "is_favourite"          => $this->when(isset($this->is_favourite), $this->is_favourite),
            "show_image"            => $this->show_image,
            "profile_visits_count"  => $this->profile_visits_count,
            "ratings_avg_rating"    => $this->ratings_avg_rating,
            "ratings_count"         => $this->ratings_count,
            "skills"                => $this->skills?->pluck('name') ?? []
        ];

        return array_merge( $record, $this->data_values);
    }

    public function userImage()
    {
        $default_image  = '/images/default-user-130x130.png';
        $image_path     = !empty($this->image) ? getProfileImageURL($this->image, '130x130') : '';
        $path           = !empty($image_path) ? '/storage/' . $image_path : $default_image;

        return asset($path);
    }
}


