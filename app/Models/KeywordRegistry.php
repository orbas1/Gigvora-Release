<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordRegistry extends Model
{
    use HasFactory;

    protected $table = 'keyword_registry';

    protected $fillable = [
        'keyword',
        'normalized',
        'source_type',
        'source_id',
        'country',
        'frequency',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];
}
