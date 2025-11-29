<?php

namespace App\Http\Resources\Dispute;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisputeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = getUserRole();
        $user_role = $user['roleName'];
        $role_id = $this->disputeCreator->role_id;

        return [
            'id' => $this->id,
            'price' =>  priceFormat($this->price),
            'data' => date(setting('_general.date_format') ?? 'm d, Y', strtotime($this->created_at)),
            'name' => getRoleById($role_id) == $user_role ? $this->disputeReceiver->short_name : $this->disputeCreator->short_name,
            'status' => getDisputeStatusTag($this->status)['text'],
        ];
    }

}
