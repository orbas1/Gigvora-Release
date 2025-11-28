<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [];
        if(is_array($this->resource)){
            foreach($this->resource as $file){
                $result[] = $this->media($file);
            }
        } else {
            $result = $this->media($this->resource);
        }
        
        return $result;
    }


    /**
     * Transform a single media file.
     *
     * @param mixed $file
     * @return array<string, mixed>
     */
    public function media($file) {
        return [
            "file_name" => $file?->file_name ?? '',
            "file_path" => !empty($file->file_path) ? asset('storage/'.$file->file_path) : '',
            "mime_type" => $file?->mime_type ?? '',
            "sizes" => !empty($file->sizes) ? $this->fileSizes($file->sizes) : []
        ];
    }

    /**
     * Process file sizes.
     *
     * @param mixed $fileSizes
     * @return array<string, mixed>
     */
    public function fileSizes($file){
        $sizes = [];
        foreach($file as $size => $url){
            $sizes[$size] = asset('storage/'.$url);
        }
        return $sizes;
    }
}
