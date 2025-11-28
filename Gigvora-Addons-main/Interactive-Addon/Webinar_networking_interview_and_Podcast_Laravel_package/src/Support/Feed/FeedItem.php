<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Support\Feed;

class FeedItem
{
    public function __construct(
        public string $type,
        public string $title,
        public string $summary,
        public string $url,
        public string $timestamp,
        public array $meta = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'summary' => $this->summary,
            'url' => $this->url,
            'timestamp' => $this->timestamp,
            'meta' => $this->meta,
        ];
    }
}
