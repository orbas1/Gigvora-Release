<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdentityInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                    => $this->id,
            "user_id"               => $this->user_id,
            "name"                  => $this->name,
            "contact_no"            => $this->contact_no,
            "identity_no"           => $this->identity_no,
            "address"               => $this->address,
            "identity_attachments"  => $this->attachments(),
        ];
    }

    public function attachments()
    {
        $attachments = [];
        if(!empty($this->identity_attachments)){
            $data = @unserialize($this->identity_attachments);
            
            if(!empty($data)){
                foreach($data as $media){
                    $attachments[] = asset('storage/'.$media);
                }
            }
        }
        return $attachments;
    }
}
