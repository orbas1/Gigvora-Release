<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Support\Feed;

use Jobi\WebinarNetworkingInterviewPodcast\Models\Interview;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastEpisode;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;

class FeedTransformer
{
    public static function fromWebinar(Webinar $webinar): FeedItem
    {
        return new FeedItem(
            'webinar',
            $webinar->title,
            (string) str($webinar->description)->limit(120),
            route('wnip.webinars.show', $webinar),
            $webinar->starts_at->toIso8601String(),
            ['status' => $webinar->status, 'host_id' => $webinar->host_id]
        );
    }

    public static function fromNetworking(NetworkingSession $session): FeedItem
    {
        return new FeedItem(
            'networking',
            $session->title,
            (string) str($session->description)->limit(120),
            route('wnip.networking.show', $session),
            $session->starts_at->toIso8601String(),
            ['status' => $session->status, 'host_id' => $session->host_id]
        );
    }

    public static function fromEpisode(PodcastEpisode $episode): FeedItem
    {
        return new FeedItem(
            'podcast',
            $episode->title,
            (string) str($episode->description)->limit(120),
            route('wnip.podcasts.series', $episode->series),
            ($episode->published_at ?? $episode->scheduled_for ?? now())->toIso8601String(),
            ['series_id' => $episode->podcast_series_id]
        );
    }

    public static function fromInterview(Interview $interview): FeedItem
    {
        return new FeedItem(
            'interview',
            $interview->title,
            (string) str($interview->description)->limit(120),
            route('wnip.interviews.show', $interview),
            $interview->scheduled_at->toIso8601String(),
            ['host_id' => $interview->host_id]
        );
    }
}
