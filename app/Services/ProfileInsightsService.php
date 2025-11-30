<?php

namespace App\Services;

use App\Models\Live_streamings;
use App\Models\Notification;
use App\Models\Page;
use App\Models\Posts;
use App\Models\User;
use App\Models\UtilitiesCalendarEvent;

class ProfileInsightsService
{
    public function __construct(protected TalentAiInsightsService $talentAi)
    {
    }

    public function forUser(User $user): array
    {
        $insights = [
            'active_job_applications' => $this->countActiveJobApplications($user),
            'upcoming_interviews' => $this->countUpcomingInterviews($user),
            'active_freelance_engagements' => $this->countActiveFreelanceEngagements($user),
            'managed_companies' => $this->countManagedCompanies($user),
            'upcoming_sessions' => $this->countUpcomingLiveSessions($user),
            'utilities_reminders' => $this->countUtilitiesReminders($user),
            'calendar_entries' => $this->countCalendarEntries($user),
            'utilities_tools' => $this->listUtilitiesTools(),
            'company_cards' => $this->companyCards($user),
            'opportunity_cards' => $this->opportunityCards($user),
        ];

        if ($this->talentAi->isEnabled()) {
            $insights = array_merge($insights, $this->talentAi->profileData($user));
        }

        return $insights;
    }

    protected function countActiveJobApplications(User $user): int
    {
        return Posts::where('user_id', $user->id)
            ->where('post_type', 'job')
            ->where('status', 'active')
            ->count();
    }

    protected function countUpcomingInterviews(User $user): int
    {
        return UtilitiesCalendarEvent::where('user_id', $user->id)
            ->whereIn('source', ['jobs_interview', 'interactive_interview'])
            ->where('starts_at', '>=', now()->subDay())
            ->count();
    }

    protected function countActiveFreelanceEngagements(User $user): int
    {
        return Posts::where('user_id', $user->id)
            ->whereIn('post_type', ['freelance_project', 'gig'])
            ->where('status', 'active')
            ->count();
    }

    protected function countManagedCompanies(User $user): int
    {
        return Page::where('user_id', $user->id)->count();
    }

    protected function countUpcomingLiveSessions(User $user): int
    {
        return Live_streamings::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(14))
            ->count();
    }

    protected function countUtilitiesReminders(User $user): int
    {
        return Notification::where('reciver_user_id', $user->id)
            ->where('status', '0')
            ->count();
    }

    protected function countCalendarEntries(User $user): int
    {
        $liveCount = $this->countUpcomingLiveSessions($user);

        $eventCount = Posts::where('user_id', $user->id)
            ->whereIn('post_type', ['event', 'interview'])
            ->where('status', 'active')
            ->count();

        $utilitiesEvents = UtilitiesCalendarEvent::where('user_id', $user->id)->count();

        return $liveCount + $eventCount + $utilitiesEvents;
    }

    protected function listUtilitiesTools(): array
    {
        return [
            get_phrase('Income planner'),
            get_phrase('Security checkup'),
            get_phrase('Reminder center'),
        ];
    }

    protected function companyCards(User $user): array
    {
        return Page::where('user_id', $user->id)
            ->latest('id')
            ->limit(3)
            ->get()
            ->map(function (Page $page) {
                return [
                    'title' => $page->title ?? $page->name ?? '',
                    'logo' => get_page_logo($page->logo ?? '', 'logo'),
                    'role' => get_phrase('Admin'),
                    'tags' => array_filter([$page->category ?? null]),
                ];
            })
            ->toArray();
    }

    protected function opportunityCards(User $user): array
    {
        return Posts::whereIn('post_type', ['job', 'freelance_project'])
            ->where('status', 'active')
            ->where('user_id', '!=', $user->id)
            ->latest('post_id')
            ->limit(2)
            ->get()
            ->map(function (Posts $post) {
                return [
                    'type' => strtoupper(str_replace('_', ' ', $post->post_type)),
                    'title' => ellipsis(strip_tags($post->description ?? ''), 70),
                    'company' => optional($post->getUser)->name ?? get_phrase('Company'),
                    'status' => get_phrase('Open'),
                    'link' => route('single.post', $post->post_id),
                ];
            })
            ->toArray();
    }
}

