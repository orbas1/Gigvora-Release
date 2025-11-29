<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'channel',
        'description',
        'is_active',
    ];
}
