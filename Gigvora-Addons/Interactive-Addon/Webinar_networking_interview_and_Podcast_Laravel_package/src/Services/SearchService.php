<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Interview;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastEpisode;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;

class SearchService
{
    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        $limit = (int) ($filters['per_page'] ?? 10);

        $webinars = Webinar::query()
            ->selectRaw("title, description, starts_at as date, 'webinar' as type, id")
            ->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%");

        $networking = NetworkingSession::query()
            ->selectRaw("title, description, starts_at as date, 'networking' as type, id")
            ->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%");

        $episodes = PodcastEpisode::query()
            ->selectRaw("title, description, published_at as date, 'podcast' as type, id")
            ->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%");

        $interviews = Interview::query()
            ->selectRaw("title, description, scheduled_at as date, 'interview' as type, id")
            ->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%");

        $items = $webinars->unionAll($networking)->unionAll($episodes)->unionAll($interviews);

        $paginated = $items->orderByDesc('date')->paginate($limit);

        $paginated->getCollection()->transform(function ($item) {
            return [
                'title' => $item->title,
                'type' => $item->type,
                'description' => $item->description,
                'date' => $item->date,
                'id' => $item->id,
            ];
        });

        return $paginated;
    }
}
