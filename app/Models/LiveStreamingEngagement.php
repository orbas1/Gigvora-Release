<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveStreamingEngagement extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_streaming_id',
        'user_id',
        'type',
        'amount',
        'payload',
    ];

    protected $casts = [
        'amount' => 'float',
        'payload' => 'array',
    ];

    public function stream()
    {
        return $this->belongsTo(Live_streamings::class, 'live_streaming_id', 'streaming_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

