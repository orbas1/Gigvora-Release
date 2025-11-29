<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResouce extends JsonResource
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
            "author_id"             => $this->author_id,
            "project_title"         => $this->project_title,
            "slug"                  => $this->slug,
            "project_type"          => $this->project_type,
            "project_description"   => trim(stripslashes($this->project_description), '"'),
            "project_min_price"     => $this->project_min_price,
            "project_max_price"     => $this->project_max_price,
            "address"               => getUserAddress($this->whenHas('address'), setting('_general.address_format') ?? 'state_country'),
            "project_hiring_seller" => $this->whenHas('project_hiring_seller'),
            "is_featured"           => $this->is_featured,
            // 'is_favourite'          => $this->is_favourite ?? 0,
            "is_favourite"          => $this->when(isset($this->is_favourite), $this->is_favourite),
            "status"                => $this->whenHas('status'),
            "skills"                => $this->whenLoaded('skills',          fn() => $this->skills->pluck('name')),
            'expertise_level'       => $this->whenLoaded('expertiseLevel',  fn() => $this->expertiseLevel?->name),
            'project_author'        => $this->whenLoaded('projectAuthor',   fn() => new ProjectAuthorResource($this->projectAuthor)),
            'project_location'      => $this->whenLoaded('projectLocation', fn() => $this->projectLocation?->name),
            'projectDuration'       => $this->whenLoaded('projectDuration', fn() => $this->projectDuration?->name),
            'posted_at'             => getTimeDiff($this->updated_at),
            'favourites_count'      => $this->favourites_count,
        ];

        return array_merge( $record, $this->data_values);
    }
}
