<?php

namespace Jobs\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Jobs\Models\Job;

class JobPublished
{
    use Dispatchable, SerializesModels;

    public function __construct(public Job $job)
    {
    }
}
