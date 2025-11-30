@php
    $user_info = Auth()->user();
    $recommendations = app(\App\Services\FeedRecommendationService::class)->forUser($user_info);
    $advertService = config('advertisement.enabled') ? app(\App\Services\AdvertisementSurfaceService::class) : null;
    $topFeedAd = $advertService ? $advertService->forSlot('newsfeed') : null;
    $inlineFeedAd = $advertService ? $advertService->forSlot('newsfeed_inline') : null;
    $laneAd = $advertService ? $advertService->forSlot('newsfeed_lane') : null;
    $interviewTimeline = app(\App\Services\InterviewTimelineService::class)->upcoming($user_info, 3);
    $interviewReminders = app(\App\Services\InterviewReminderService::class)->remindersFor($user_info, 3);
    $talentAiSummary = app(\App\Services\TalentAiInsightsService::class)->feedSummary($user_info);
@endphp

<div class="gv-feed-hub">
    <header class="gv-feed-header">
        <div class="gv-feed-header__meta">
            <span class="gv-pill-page-label">
                <span class="gv-pill-page-label-dot"></span>
                {{ get_phrase('Live Feed') }}
            </span>
            <h1 class="gv-main-heading mb-1">
                {{ get_phrase('Gigvora hub') }}
            </h1>
            <p class="gv-main-heading-sub">
                {{ get_phrase('Posts, jobs, gigs, live sessions, utilities and sponsors curated for your graph.') }}
            </p>
        </div>
        <div class="gv-feed-header__actions">
            <a href="{{ Route::has('utilities.hub') ? route('utilities.hub') : url('/utilities/hub') }}" class="gv-btn gv-btn-ghost">
                <i class="fa-solid fa-bolt"></i> {{ get_phrase('Utilities hub') }}
            </a>
            <a href="{{ Route::has('liveCenter.hub') ? route('liveCenter.hub') : route('event') }}" class="gv-btn gv-btn-primary">
                <i class="fa-solid fa-podcast"></i> {{ get_phrase('Live center') }}
            </a>
        </div>
    </header>

    @include('components.utilities.quick-tools', ['context' => 'feed'])

    @if($interviewTimeline->isNotEmpty())
        @include('frontend.main_content.interview_timeline', ['entries' => $interviewTimeline])
    @endif
    @if($interviewReminders->isNotEmpty())
        @include('frontend.main_content.interview_reminders', ['reminders' => $interviewReminders])
    @endif

    <div class="gv-feed-stack">
        @include('frontend.story.index')

        @include('frontend.main_content.create_post')

        @includeWhen(!empty($talentAiSummary), 'frontend.main_content.talent_ai_summary', ['summary' => $talentAiSummary])

        @include('frontend.main_content.recommendation_lanes', [
            'recommendations' => $recommendations,
            'laneAd' => $laneAd,
        ])

        @if ($topFeedAd)
            <div class="gv-card gv-feed-card">
                @include('advertisement::components.ad_feed_card', ['ad' => $topFeedAd])
            </div>
        @endif

        <div id="timeline-posts" class="gv-feed-stack">
            @include('frontend.main_content.posts', ['type' => 'user_post', 'inlineAd' => $inlineFeedAd])
        </div>
    </div>
</div>

@include('frontend.main_content.scripts')
