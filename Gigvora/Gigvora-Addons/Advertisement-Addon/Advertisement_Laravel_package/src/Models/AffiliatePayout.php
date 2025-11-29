<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliatePayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'affiliate_id',
        'amount',
        'status',
        'requested_at',
        'processed_at',
        'notes'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
