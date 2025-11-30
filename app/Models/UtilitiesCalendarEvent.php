<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilitiesCalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source',
        'source_id',
        'title',
        'subtitle',
        'description',
        'starts_at',
        'ends_at',
        'location',
        'status',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

