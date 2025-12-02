<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_id',
        'target_type',
        'target_id',
        'action',
        'changes',
        'source',
    ];

    protected $casts = [
        'changes' => 'array',
    ];
}

