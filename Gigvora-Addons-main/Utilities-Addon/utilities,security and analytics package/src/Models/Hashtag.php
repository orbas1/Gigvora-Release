<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use ProNetwork\Support\Helpers\TagHelper;

class Hashtag extends BaseModel
{
    protected $table = 'pro_network_hashtags';

    protected $fillable = [
        'tag',
        'normalized',
        'usage_count',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(HashtagAssignment::class);
    }

    public function scopeNormalized($query, string $tag)
    {
        return $query->where('normalized', TagHelper::normalize($tag));
    }
}
