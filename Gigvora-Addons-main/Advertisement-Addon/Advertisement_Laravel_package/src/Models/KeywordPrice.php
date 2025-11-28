<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'cpc',
        'cpa',
        'cpm'
    ];
}
