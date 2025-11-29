<?php

namespace ProNetwork\Models;

class BadWordRule extends BaseModel
{
    protected $table = 'pro_network_bad_word_rules';

    protected $fillable = [
        'name',
        'action',
        'applies_to',
        'active',
    ];

    protected $casts = [
        'applies_to' => 'array',
        'active' => 'boolean',
    ];
}
