<?php

namespace ProNetwork\Models;

class FileScan extends BaseModel
{
    protected $table = 'pro_network_file_scans';

    protected $fillable = [
        'path',
        'file_hash',
        'scanner_name',
        'status',
        'details',
        'scanned_at',
    ];

    protected $casts = [
        'details' => 'array',
        'scanned_at' => 'datetime',
    ];
}
