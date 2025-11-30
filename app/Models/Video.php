<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'privacy',
        'category',
        'mobile_app_image',
        'file',
        'view',
        'duration_seconds',
        'is_reel',
    ];

    protected $casts = [
        'is_reel' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function (self $video) {
            $video->assignVideoMetadata();
        });
    }

    public function getUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    protected function assignVideoMetadata(): void
    {
        if (empty($this->file) || filter_var($this->file, FILTER_VALIDATE_URL)) {
            return;
        }

        $path = public_path('storage/videos/' . $this->file);
        if (!is_file($path)) {
            return;
        }

        $service = app(\App\Services\MediaDurationService::class);
        $duration = $service->getDurationSeconds($path);
        $this->duration_seconds = $duration;
        $this->is_reel = $service->isReel($duration);

        // Sync video category if not explicitly set
        if (empty($this->category)) {
            $this->category = $this->is_reel ? 'shorts' : 'video';
        }
    }
}
