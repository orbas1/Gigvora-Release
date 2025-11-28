<?php

namespace App\Http\Resources\ProjectDetail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "id"                    => $this->whenHas('id'),
            "author_id"             => $this->whenHas('author_id'),
            "project_title"         => $this->whenHas('project_title'),
            "slug"                  => $this->whenHas('slug'),
            "project_type"          => $this->whenHas('project_type'),
            "project_payment_mode"  => $this->whenHas('project_payment_mode'),
            "project_payout_type"   => $this->whenHas('project_payout_type'),
            "project_hiring_seller" => $this->whenHas('project_hiring_seller'),
            "project_description"   => trim(stripslashes($this->project_description), '"'),
            'project_min_price'     => priceFormat($this->project_min_price),
            'project_max_price'     => priceFormat($this->project_max_price),
            "address"               => !empty($this->address) ? getUserAddress($this->address, setting('_general.address_format') ?? 'state_country') : '',
            "is_featured"           => $this->is_featured,
            'is_favourite'          => $this->is_favourite ?? 0,
            "status"                => $this->status,
            "skills"                => $this->whenLoaded('skills',          fn() => $this->skills->pluck('name')),
            'expertise_level'       => $this->whenLoaded('expertiseLevel',  fn() => $this->expertiseLevel?->name),
            'project_author'        => $this->whenLoaded('projectAuthor', function () {
                return [
                    'name'          => $this->projectAuthor->short_name,
                    'full_name'     => $this->projectAuthor->full_name,
                    'image'         => !empty($this->projectAuthor->image) ? asset('storage/'.$this->projectAuthor->image) : null,
                    'description'   => $this->projectAuthor->description,
                    'created_at'    => $this->projectAuthor->created_at,
                ];
            }),
            'project_location'      => $this->whenLoaded('projectLocation', fn() => $this->projectLocation?->name),
            'project_duration'      => $this->whenLoaded('projectDuration', fn() => $this->projectDuration?->name),
            'category'              => $this->whenLoaded('category',        fn() => $this->category?->name),
            'posted_at'             => getTimeDiff($this->updated_at),
            'language'              => $this->languages->map(function ($language) {
                                            return [
                                                'name'  => $language->name,
                                            ];
                                        }),
            'submit_proposal'       => $this->is_applied ?? false, 
        ];
    }

    protected function hasSubmittedProposal()
    {
        return $this->proposals()->where('author_id', auth()->id())->exists();
    }
}
