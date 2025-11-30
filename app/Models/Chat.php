<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ProNetwork\Models\Reaction;
use App\Models\Media_files;

class Chat extends Model
{
    use HasFactory;

    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function mediaFiles(): HasMany
    {
        return $this->hasMany(Media_files::class, 'chat_id');
    }
}
