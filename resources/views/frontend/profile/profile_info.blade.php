@php
    $media_files = \App\Models\Media_files::where('user_id', Auth()->user()->id)
        ->whereNull('story_id')
        ->whereNull('product_id')
        ->whereNull('page_id')
        ->whereNull('group_id')
        ->whereNull('chat_id')
        ->take(9)
        ->orderBy('id', 'desc')
        ->get();

    $insights = $profileInsights ?? [];
    $utilitiesActions = collect(app(\App\Services\UtilitiesQuickToolsService::class)->actionsFor('profile'));
    $interviewTimeline = app(\App\Services\InterviewTimelineService::class)->upcoming($user_info, 4);
    $interviewReminders = app(\App\Services\InterviewReminderService::class)->remindersFor($user_info, 3);
@endphp

<aside class="sidebar space-y-4">
    <div class="gv-card space-y-3" id="my-profile-info">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="gv-heading text-base mb-0">{{ get_phrase('About') }}</h4>
            @if (isset($type) && $type == 'my_account')
                <button onclick="toggleBio(this, '.edit-bio-form')" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Edit Bio') }}</button>
            @endif
        </div>
        <div class="my-about">
            @php echo script_checker($user_info->about) @endphp
        </div>
        <form class="ajaxForm d-hidden edit-bio-form" action="{{ route('profile.about', ['action_type' => 'update']) }}"
            method="post">
            @CSRF
            <div class="mb-3">
                <textarea name="about" class="form-control">{{ $user_info->about }}</textarea>
            </div>
            <div>
                <button type="submit" class="gv-btn gv-btn-primary w-100">{{ get_phrase('Save Bio') }}</button>
            </div>
        </form>
        @include('frontend.profile.my_info')
    </div>

    <div class="gv-card space-y-3">
        <h4 class="gv-heading text-base mb-0">{{ get_phrase('Quick links') }}</h4>
        <ul class="list-unstyled gv-profile-quick-links">
            <li><a href="{{ route('profile.photos') }}"><i class="fa-solid fa-clapperboard-play"></i>{{ get_phrase('Photos & Reels') }}</a></li>
            <li><a href="{{ route('profile.videos') }}"><i class="fa-solid fa-film"></i>{{ get_phrase('Videos') }}</a></li>
            <li><a href="{{ route('profile.mediaHub') }}"><i class="fa-solid fa-layer-group"></i>{{ get_phrase('Media Hub') }}</a></li>
            <li><a href="{{ route('profile.savePostList') }}"><i class="fa-solid fa-bookmark"></i>{{ get_phrase('Saved posts') }}</a></li>
            <li><a href="{{ route('profile.checkins_list') }}"><i class="fa-solid fa-location-dot"></i>{{ get_phrase('Check-ins') }}</a></li>
        </ul>
    </div>

    <div class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="gv-heading text-base mb-0">{{ get_phrase('Utilities quick tools') }}</h4>
            <a href="{{ Route::has('utilities.hub') ? route('utilities.hub') : url('/utilities/hub') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Open hub') }}</a>
        </div>
        <div class="gv-profile-utilities">
            @foreach ($utilitiesActions as $action)
                <a href="{{ $action['href'] }}" class="gv-pill gv-profile-utility-pill">
                    <i class="{{ $action['icon'] }}"></i>
                    <span>{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    @php
        $talentSidebar = $insights['talent_ai_sidebar']['rows'] ?? [];
    @endphp

    @if(!empty($talentSidebar))
        <div class="gv-card space-y-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="gv-heading text-base mb-0">{{ get_phrase('Talent & AI') }}</h4>
                <a href="{{ route('addons.talent_ai.headhunters.dashboard') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Launch') }}</a>
            </div>
            <ul class="list-unstyled space-y-2 mb-0">
                @foreach($talentSidebar as $row)
                    <li class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-sm gv-muted mb-0">{{ $row['label'] ?? get_phrase('Metric') }}</p>
                            <p class="fw-semibold mb-0">{{ $row['value'] ?? '—' }}{{ $row['suffix'] ?? '' }}</p>
                        </div>
                        @if(!empty($row['href']))
                            <a href="{{ $row['href'] }}" class="gv-link text-sm">{{ get_phrase('Manage') }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($interviewTimeline->isNotEmpty())
        <div class="gv-card space-y-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="gv-heading text-base mb-0">{{ get_phrase('Interview timeline') }}</h4>
                @if (Route::has('utilities.calendar.index'))
                    <a href="{{ route('utilities.calendar.index') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Calendar') }}</a>
                @endif
            </div>
            <div class="space-y-2">
                @foreach($interviewTimeline as $entry)
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-semibold mb-0">{{ $entry['title'] }}</p>
                            <p class="text-muted small mb-0">
                                {{ $entry['date']->format('M d · H:i') }} · {{ ucfirst($entry['status']) }}
                            </p>
                        </div>
                        @if (!empty($entry['cta_url']))
                            <a href="{{ $entry['cta_url'] }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Open') }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if($interviewReminders->isNotEmpty())
        <div class="gv-card space-y-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="gv-heading text-base mb-0">{{ get_phrase('Interview reminders') }}</h4>
                @if (Route::has('utilities.calendar.index'))
                    <a href="{{ route('utilities.calendar.index') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Manage') }}</a>
                @endif
            </div>
            <div class="space-y-2">
                @foreach($interviewReminders as $reminder)
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="fw-semibold mb-0">{{ $reminder['title'] }}</p>
                            <p class="text-muted small mb-0">
                                {{ get_phrase('Reminds :lead', ['lead' => $reminder['lead_time']]) }} · {{ $reminder['starts_at']->format('M d · H:i') }}
                            </p>
                        </div>
                        @if(!empty($reminder['cta_url']))
                            <a href="{{ $reminder['cta_url'] }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Open') }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="gv-heading text-base mb-0">{{ get_phrase('Photos & Reels / Videos') }}</h4>
            <a href="{{ route('profile.photos') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See all') }}</a>
        </div>
        <div id="sidebarPhotoAndVideos" class="row row-cols-3 g-1">
            @include('frontend.profile.sidebar_photos_and_videos')
        </div>
    </div>

    <div class="gv-card space-y-3 friend-widget">
        @php
            $friends = DB::table('friendships')
                ->where(function ($query) {
                    $query->where('accepter', Auth()->user()->id)->orWhere('requester', Auth()->user()->id);
                })
                ->where('is_accepted', 1)
                ->orderBy('friendships.importance', 'desc');
        @endphp
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="gv-heading text-base mb-0">{{ get_phrase('Friends') }}</h4>
                <span class="text-xs gv-muted">{{ $friends->get()->count() }} {{ get_phrase('connections') }}</span>
            </div>
            <a href="{{ route('profile.friends') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See all') }}</a>
        </div>
        <div class="row row-cols-3 g-2">
            @foreach ($friends->take(6)->get() as $friend)
                @if ($friend->requester == Auth()->user()->id)
                    @php
                        $friends_user_data = DB::table('users')
                            ->where('id', $friend->accepter)
                            ->first();
                    @endphp
                @else
                    @php
                        $friends_user_data = DB::table('users')
                            ->where('id', $friend->requester)
                            ->first();
                    @endphp
                @endif
                @if (isset($friends_user_data->id))
                    <div class="col">
                        <a href="{{ route('user.profile.view', $friends_user_data->id) }}" class="friend d-block text-center">
                            <img width="100%" class="rounded" src="{{ get_user_image($friends_user_data->photo, 'optimized') }}"
                                alt="">
                            <h6 class="small mt-2">{{ $friends_user_data->name }}</h6>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</aside>

