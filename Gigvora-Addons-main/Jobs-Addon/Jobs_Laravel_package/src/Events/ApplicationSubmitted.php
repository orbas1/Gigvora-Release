<?php

namespace Jobs\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Jobs\Models\JobApplication;

class ApplicationSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(public JobApplication $application)
    {
    }
}
