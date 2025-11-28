<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id'       => $this->id,
            'profile_id'    => $this->activeProfile->id,
            'user_type'     => $this->activeProfile->role_id == 3 ? 'seller' : ($this->activeProfile->role_id == 2 ? 'buyer' : null),
            'first_name'    => $this->activeProfile->first_name,
            'last_name'     => $this->activeProfile->last_name,
            'full_name'     => $this->activeProfile->full_name,
            'short_name'    => $this->activeProfile->short_name,
            'slug'          => $this->activeProfile->slug,
            'image'         => !empty($this->activeProfile->image) ? $this->profileImage($this->activeProfile->image) : asset('images/default-user.jpg'),
            'description'   => $this->activeProfile->description,
            'seller_type'   => $this->activeProfile->seller_type,
            'english_level' => $this->activeProfile->english_level,
            'skills'        => $this->activeProfile->skills?->pluck('name') ?? [],
            'languages'     => $this->activeProfile->languages?->pluck('name') ?? [],
            'tagline'       => $this->activeProfile->tagline,
            'country'       => $this->activeProfile->country,
            'hourly_rate'   => priceFormat($this->activeProfile->user->userAccountSetting->hourly_rate ?? 0),
            'address'       => getUserAddress($this->activeProfile->address, setting('_general.address_format') ?? 'state_country'),
            'zipcode'       => $this->activeProfile->zipcode,
            'is_verified'   => $this->email_verified_at ? true : false,
            'wallet_amount' => priceFormat($this->activeProfile->userWallet->amount ?? 0),
            'show_image'    => $this->activeProfile->user->userAccountSetting->show_image,
            // 'identify_verification'   => $this->activeProfile->user->userAccountSetting->verification,
        ];
    }

    public function profileImage($image)
    {
        $image_record  = @unserialize($image);
        if( !empty($image_record) ){
            return !empty($image_record['url']) ? asset('storage/' . $image_record['url']) : asset('images/default-user.jpg');
        } else {
            return asset('storage/' . $image);
        }
    }
}
