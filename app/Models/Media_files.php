<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media_files extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'story_id',
        'album_id',
        'file_name',
        'product_id',
        'page_id',
        'group_id',
        'chat_id',
        'file_type',
        'privacy',
        'created_at',
        'updated_at',
        'album_image_id',
        'duration_seconds',
        'is_reel',
        'resolution_preset',
        'processing_manifest',
    ];

    protected $casts = [
        'is_reel' => 'boolean',
        'processing_manifest' => 'array',
    ];

    protected static function booted()
    {
        static::saving(function (self $media) {
            $media->assignVideoMetadata();
        });
    }

    public function post(){
        return $this->belongsTo(Posts::class,'post_id', 'post_id');
    }

    public function scopePhotosAndReels($query)
    {
        return $query->where(function ($subQuery) {
            $subQuery->where('file_type', 'image')
                ->orWhere(function ($inner) {
                    $inner->where('file_type', 'video')
                        ->where('is_reel', true);
                });
        });
    }

    public function scopeLongVideos($query)
    {
        return $query->where('file_type', 'video')
            ->where(function ($videoScope) {
                $videoScope->where('is_reel', false)
                    ->orWhereNull('is_reel');
            });
    }

    protected function assignVideoMetadata(): void
    {
        if ($this->file_type !== 'video') {
            return;
        }

        if (!empty($this->duration_seconds) && $this->is_reel !== null) {
            return;
        }

        $path = $this->guessVideoPath();
        if (!$path) {
            return;
        }

        $service = app(\App\Services\MediaDurationService::class);
        $duration = $service->getDurationSeconds($path);
        $this->duration_seconds = $duration;
        $this->is_reel = $service->isReel($duration);
    }

    protected function guessVideoPath(): ?string
    {
        if (empty($this->file_name) || filter_var($this->file_name, FILTER_VALIDATE_URL)) {
            return null;
        }

        $candidates = [
            public_path('storage/post/videos/' . $this->file_name),
            public_path('storage/story/videos/' . $this->file_name),
            public_path('storage/chat/videos/' . $this->file_name),
            public_path('storage/videos/' . $this->file_name),
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
