<?php

namespace Jobs\Support\Feed;

use Jobs\Models\Job;

class JobFeedTransformer
{
    public static function toFeedItem(Job $job): array
    {
        return [
            'type' => 'job_post',
            'id' => $job->id,
            'title' => $job->title,
            'summary' => str($job->description)->limit(200)->toString(),
            'url' => route('jobs.show', $job),
            'owner_id' => $job->company_id,
            'published_at' => optional($job->published_at ?? $job->created_at)->toIso8601String(),
            'tags' => $job->tag_list,
            'location' => $job->location,
        ];
    }
}
