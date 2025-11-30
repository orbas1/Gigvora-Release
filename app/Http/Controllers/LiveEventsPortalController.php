<?php

namespace App\Http\Controllers;

use App\Services\LiveEventsExperienceService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LiveEventsPortalController extends Controller
{
    public function __construct(
        protected LiveEventsExperienceService $liveExperience,
    ) {
    }

    public function hub(): View
    {
        return view('live.hub', [
            'overview' => $this->liveExperience->hubOverview(),
        ]);
    }

    public function webinars(): RedirectResponse
    {
        return redirect()->route('wnip.webinars.index');
    }

    public function recordings(): RedirectResponse
    {
        return redirect()->route('wnip.webinars.recordings');
    }
}


