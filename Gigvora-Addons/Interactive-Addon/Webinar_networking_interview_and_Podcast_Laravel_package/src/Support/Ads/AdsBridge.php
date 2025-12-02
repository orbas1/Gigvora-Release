<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Support\Ads;

use App\Services\AdvertisementSurfaceService;

class AdsBridge
{
    public static function placementsFor(string $slot, ?int $contextId = null): array
    {
        $service = app(AdvertisementSurfaceService::class);

        $payload = $service->placements($slot, [
            'context' => 'interactive_webinar',
            'context_id' => $contextId,
        ]);

        return is_array($payload) ? $payload : [];
    }
}
