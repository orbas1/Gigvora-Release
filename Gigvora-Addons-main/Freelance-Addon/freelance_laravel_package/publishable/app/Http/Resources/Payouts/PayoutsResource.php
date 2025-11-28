<?php

namespace App\Http\Resources\Payouts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayoutsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $detail = unserialize($this->detail);
        return [ 
            "id"                => $this->id,
            "seller_id"         => $this->seller_id,
            "amount"            => $this->amount,
            "payment_method"    => $this->payment_method,
            "detail"            => $detail,
            "status"            => $this->status

        ];
    }
}
