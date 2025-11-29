<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->short_name,
            'image' => $this->userImage(),
            'wallet_amount' => priceFormat($this->userWallet?->amount ?? 0) 
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
