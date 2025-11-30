<?php

namespace App\Services;

use App\Models\Posts;
use App\Models\User;

class ProfileJourneyService
{
    public function cardsFor(User $user): array
    {
        return [
            [
                'title' => get_phrase('Boost your profile'),
                'steps' => [
                    [
                        'label' => get_phrase('Add your latest projects or gigs'),
                        'cta' => get_phrase('Update profile'),
                        'link' => route('profile'),
                        'completed' => $this->hasRecentFreelancePosts($user),
                    ],
                    [
                        'label' => get_phrase('Publish a job or interview invite'),
                        'cta' => get_phrase('Post now'),
                        'link' => route('create.job'),
                        'completed' => $this->hasRecentJobPosts($user),
                    ],
                ],
            ],
            [
                'title' => get_phrase('Grow your network'),
                'steps' => [
                    [
                        'label' => get_phrase('Invite colleagues to your company page'),
                        'cta' => get_phrase('Manage pages'),
                        'link' => route('pages'),
                        'completed' => false,
                    ],
                    [
                        'label' => get_phrase('Host a live session or webinar'),
                        'cta' => get_phrase('Go live'),
                        'link' => route('live.stream'),
                        'completed' => false,
                    ],
                ],
            ],
            [
                'title' => get_phrase('Stay organized'),
                'steps' => [
                    [
                        'label' => get_phrase('Review your upcoming interviews'),
                        'cta' => get_phrase('View calendar'),
                        'link' => route('calendar.index'),
                        'completed' => false,
                    ],
                    [
                        'label' => get_phrase('Check Utilities reminders'),
                        'cta' => get_phrase('Open utilities'),
                        'link' => url('#utilities'),
                        'completed' => false,
                    ],
                ],
            ],
        ];
    }

    protected function hasRecentFreelancePosts(User $user): bool
    {
        return Posts::where('user_id', $user->id)
            ->whereIn('post_type', ['freelance_project', 'gig'])
            ->where('created_at', '>=', now()->subDays(30)->timestamp)
            ->exists();
    }

    protected function hasRecentJobPosts(User $user): bool
    {
        return Posts::where('user_id', $user->id)
            ->where('post_type', 'job')
            ->where('created_at', '>=', now()->subDays(30)->timestamp)
            ->exists();
    }
}

