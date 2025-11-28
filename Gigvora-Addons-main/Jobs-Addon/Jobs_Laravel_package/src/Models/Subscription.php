<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'plan',
        'job_credits',
        'renews_at',
        'status',
        'payment_reference',
    ];

    protected $casts = [
        'renews_at' => 'datetime',
    ];
}
