<?php

namespace ProNetwork\Models;

class BadWord extends BaseModel
{
    protected $table = 'pro_network_bad_words';

    protected $fillable = [
        'phrase',
        'severity',
        'replacement',
    ];
}
