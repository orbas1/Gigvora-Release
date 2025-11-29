<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Media\MediaResource;
class GigResource extends JsonResource
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
            "id"                 => $this->id,
            "author_id"          => $this->author_id,
            "title"              => $this->title,
            "slug"               => $this->slug,
            "user_avatar"        => asset('storage/'.$this->gigAuthor?->image),
            "address"            => $this->when($this->address, getUserAddress($this->address, setting('_general.address_format') ?? 'state_country')),
            "attachments"        => !empty($this->attachments['files']) ? [ 'video_url' => $this->attachments['video_url'], 'files' => new MediaResource($this->attachments['files']) ]: null,
            "is_featured"        => $this->is_featured == 1 ? true : false,
            "is_favourite"       => $this->when(isset($this->is_favourite), $this->is_favourite),
            "status"             => $this->status,
            "ratings_avg_rating" => $this->whenNotNull($this->ratings_avg_rating),
            "ratings_count"      => $this->whenNotNull($this->ratings_count),
            "gig_visits_count"   => $this->whenNotNull($this->gig_visits_count),
            "minimum_price"      => $this->whenNotNull($this->minimum_price),
            "auther"             => $this->gigAuthor?->short_name,
            "auther_slug"        => $this->gigAuthor?->slug,
            "verify_auther"      => $this->gigAuthor?->user?->userAccountSetting?->verification,
        ];
        
        return array_merge( $record, $this->data_values);
    }
}
