<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creative extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'ad_group_id',
        'type',
        'title',
        'body',
        'destination_url',
        'media_path',
        'status',
        'cta',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function adGroup()
    {
        return $this->belongsTo(AdGroup::class);
    }
}
