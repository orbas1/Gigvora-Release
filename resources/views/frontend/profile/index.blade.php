
@php
    $jobsEnabled = config('jobs.features.enabled');
    $employerRoles = (array) config('jobs.roles.employer_access', []);
    $currentRole = auth()->user()->user_role ?? null;
    $canPostJobs = $jobsEnabled && $currentRole && in_array($currentRole, $employerRoles, true);

    $profileQuickActions = collect([
        [
            'icon' => 'fa-solid fa-briefcase',
            'label' => get_phrase('Post job'),
            'href' => ($canPostJobs && Route::has('create.job')) ? route('create.job') : (Route::has('jobs.index') ? route('jobs.index') : url('/jobs')),
            'enabled' => $canPostJobs,
        ],
        [
            'icon' => 'fa-solid fa-handshake-angle',
            'label' => get_phrase('Launch gig'),
            'href' => Route::has('freelance.seller.gigs.create') ? route('freelance.seller.gigs.create') : url('/freelance/dashboard'),
            'enabled' => addon_status('freelance') == 1,
        ],
        [
            'icon' => 'fa-solid fa-podcast',
            'label' => get_phrase('Go live'),
            'href' => Route::has('liveCenter.hub') ? route('liveCenter.hub') : route('event'),
            'enabled' => true,
        ],
        [
            'icon' => 'fa-solid fa-bolt',
            'label' => get_phrase('Utilities hub'),
            'href' => Route::has('utilities.hub') ? route('utilities.hub') : url('/utilities/hub'),
            'enabled' => true,
        ],
    ])->where('enabled', true)->values();
@endphp

<div class="profile-wrap space-y-5">
    <div class="gv-card p-0 overflow-hidden profile-cover">
        <div class="profile-header relative" style="background-image: url('{{ get_cover_photo($user_info->cover_photo) }}');">
            <div class="profile-header__overlay"></div>
            <div class="profile-header__actions">
                <button
                    onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.profile.edit_profile']) }}', '{{ get_phrase('Edit your profile') }}');"
                    class="gv-btn gv-btn-ghost" data-bs-toggle="modal" data-bs-target="#edit-profile">
                    <i class="fa fa-pencil"></i> {{ get_phrase('Edit Profile') }}
                </button>
                <button
                    onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.profile.edit_cover_photo']) }}', '{{ get_phrase('Update your cover photo') }}');"
                    class="gv-btn gv-btn-ghost">
                    <i class="fa fa-camera"></i> {{ get_phrase('Edit Cover Photo') }}
                </button>
            </div>
        </div>

        <div class="profile-meta px-4 py-6 bg-[var(--gv-color-surface)]">
            <div class="gv-profile-meta-grid">
                <div class="profile-avatar d-flex align-items-center gap-3">
                    <div class="gv-profile-avatar-lg gv-avatar--photo">
                        <div class="gv-profile-avatar-inner">
                            <img src="{{ get_user_image($user_info->photo, 'optimized') }}" alt="{{ $user_info->name }}">
                        </div>
                    </div>
                    <div>
                        @php
                            $user_name = \App\Models\Users::where('id', auth()->user()->id)->value('name');
                        @endphp
                        <h3 class="gv-profile-name mb-1">{{ $user_name }}</h3>
                        @if(auth()->user()->profile_status == 'lock')
                            <span class="gv-pill gv-pill--success">
                                <i class="fa-solid fa-shield"></i> {{ get_phrase('You locked your profile') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="ms-auto d-flex flex-wrap gap-2">
                    <button class="gv-btn gv-btn-primary" data-bs-toggle="modal" data-bs-target="#edit-profile">
                        {{ get_phrase('Update Info') }}
                    </button>
                    <div class="post-controls dropdown dotted profile_tab_set">
                        <button class="gv-btn gv-btn-ghost dropdown-toggle" id="profileActions" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileActions">
                            <li>
                                @if(auth()->user()->profile_status == 'lock')
                                    <a class="dropdown-item" href="{{ route('profile.profileUnlock') }}">
                                        <i class="fa-solid fa-lock-open"></i> {{ get_phrase('Unlock Profile') }}
                                    </a>
                                @else
                                    <a class="dropdown-item" href="{{ route('profile.profileLock') }}">
                                        <i class="fa-solid fa-lock"></i> {{ get_phrase('Lock Profile') }}
                                    </a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="gv-card p-0 gv-profile-snapshot">
                    <div class="d-flex flex-wrap align-items-start">
                        <div class="w-100 w-xl-1/2 p-6 border-end border-[var(--gv-color-border)]">
                            <p class="gv-eyebrow mb-1">{{ get_phrase('Opportunities') }}</p>
                            <h3 class="gv-heading text-lg mb-3">{{ get_phrase('Jobs & Freelance Projects') }}</h3>
                            <div class="space-y-3">
                                <div class="gv-card space-y-1">
                                    <p class="text-sm gv-muted mb-0">{{ get_phrase('Active job applications') }}</p>
                                    <h4 class="text-2xl font-semibold mb-0">{{ $profileInsights['active_job_applications'] ?? '—' }}</h4>
                                </div>
                                <div class="gv-card space-y-1">
                                    <p class="text-sm gv-muted mb-0">{{ get_phrase('Interview invitations') }}</p>
                                    <h4 class="text-2xl font-semibold mb-0">{{ $profileInsights['upcoming_interviews'] ?? '—' }}</h4>
                                </div>
                                <div class="gv-card space-y-1">
                                    <p class="text-sm gv-muted mb-0">{{ get_phrase('Freelance engagements') }}</p>
                                    <h4 class="text-2xl font-semibold mb-0">{{ $profileInsights['active_freelance_engagements'] ?? '—' }}</h4>
                                </div>
                                <div class="gv-card space-y-1">
                                    <p class="text-sm gv-muted mb-0">{{ get_phrase('Companies administered') }}</p>
                                    <h4 class="text-2xl font-semibold mb-0">{{ $profileInsights['managed_companies'] ?? '—' }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 w-xl-1/2 p-6">
                            <p class="gv-eyebrow mb-1">{{ get_phrase('Live & Utilities') }}</p>
                            <h3 class="gv-heading text-lg mb-3">{{ get_phrase('Events, Interviews & Tools') }}</h3>
                            <div class="space-y-3">
                                <div class="gv-card space-y-1">
                                    <p class="text-sm gv-muted mb-0">{{ get_phrase('Upcoming webinars/podcasts') }}</p>
                                    <h4 class="text-2xl font-semibold mb-0">{{ $profileInsights['upcoming_sessions'] ?? '—' }}</h4>
                                </div>
                                <div class="gv-card space-y-1">
                                    <p class="text-sm gv-muted mb-0">{{ get_phrase('Utilities reminders') }}</p>
                                    <h4 class="text-2xl font-semibold mb-0">{{ $profileInsights['utilities_reminders'] ?? '—' }}</h4>
                                </div>
                                <div class="gv-card space-y-1">
                                    <p class="text-sm gv-muted mb-0">{{ get_phrase('Calendar entries') }}</p>
                                    <h4 class="text-2xl font-semibold mb-0">{{ $profileInsights['calendar_entries'] ?? '—' }}</h4>
                                </div>
                                <div class="gv-card space-y-1">
                                    <p class="text-sm gv-muted mb-0">{{ get_phrase('Recommended tools') }}</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @forelse(($profileInsights['utilities_tools'] ?? []) as $tool)
                                            <span class="gv-pill">{{ $tool }}</span>
                                        @empty
                                            <span class="gv-muted text-sm">{{ get_phrase('No utilities linked yet') }}</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $talentMetrics = $profileInsights['talent_ai_metrics'] ?? null;
                @endphp

                @if (!empty($talentMetrics))
                    <div class="gv-card gv-profile-snapshot mt-4">
                        <div class="p-6">
                            <p class="gv-eyebrow mb-1">{{ get_phrase('Talent & AI intelligence') }}</p>
                            <h3 class="gv-heading text-lg mb-4">{{ get_phrase('Mandates, Launchpad, Volunteering & AI workspace') }}</h3>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="gv-card space-y-1 h-100">
                                        <p class="text-sm gv-muted mb-0">{{ get_phrase('Open mandates') }}</p>
                                        <h4 class="text-2xl fw-semibold mb-1">{{ $talentMetrics['headhunter']['open_mandates'] ?? 0 }}</h4>
                                        <p class="text-xs gv-muted mb-2">{{ get_phrase('Active candidates') }}: {{ $talentMetrics['headhunter']['pipeline_active'] ?? 0 }}</p>
                                        <a href="{{ $talentMetrics['headhunter']['link'] ?? route('addons.talent_ai.headhunters.dashboard') }}" class="gv-link text-sm">{{ get_phrase('View pipeline') }}</a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="gv-card space-y-2 h-100">
                                        <p class="text-sm gv-muted mb-0">{{ get_phrase('Launchpad programme') }}</p>
                                        <h4 class="text-base fw-semibold mb-0">{{ $talentMetrics['launchpad']['title'] ?? get_phrase('Not enrolled') }}</h4>
                                        @if (!empty($talentMetrics['launchpad']['progress']))
                                            @php $progress = $talentMetrics['launchpad']['progress']; @endphp
                                            <div class="gv-progress mt-2 mb-1">
                                                <span style="width: {{ $progress['percent'] ?? 0 }}%;"></span>
                                            </div>
                                            <p class="text-xs gv-muted mb-0">{{ get_phrase('Completed') }} {{ $progress['completed'] }}/{{ $progress['total'] ?? 0 }} ({{ $progress['percent'] ?? 0 }}%)</p>
                                        @else
                                            <p class="text-sm gv-muted mb-0">{{ get_phrase('No modules in progress yet.') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="gv-card space-y-1 h-100">
                                        <p class="text-sm gv-muted mb-0">{{ get_phrase('Volunteering hours') }}</p>
                                        <h4 class="text-2xl fw-semibold mb-1">{{ $talentMetrics['volunteering']['hours'] ?? 0 }}h</h4>
                                        <p class="text-xs gv-muted mb-0">{{ get_phrase('Active applications') }}: {{ $talentMetrics['volunteering']['applications'] ?? 0 }}</p>
                                        <a href="{{ $talentMetrics['volunteering']['link'] ?? route('addons.talent_ai.volunteering.opportunities.index') }}" class="gv-link text-sm">{{ get_phrase('Discover missions') }}</a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="gv-card space-y-1 h-100">
                                        <p class="text-sm gv-muted mb-0">{{ get_phrase('AI sessions this week') }}</p>
                                        <h4 class="text-2xl fw-semibold mb-1">{{ $talentMetrics['ai']['sessions_this_week'] ?? 0 }}</h4>
                                        <p class="text-xs gv-muted mb-0">
                                            {{ get_phrase('Last tool') }}:
                                            {{ ucfirst(str_replace('_', ' ', $talentMetrics['ai']['last_tool'] ?? get_phrase('N/A'))) }}
                                            @if (!empty($talentMetrics['ai']['last_run']))
                                                · {{ $talentMetrics['ai']['last_run'] }}
                                            @endif
                                        </p>
                                        <a href="{{ $talentMetrics['ai']['link'] ?? route('addons.talent_ai.ai_workspace.index') }}" class="gv-link text-sm">{{ get_phrase('Launch workspace') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($profileQuickActions->isNotEmpty())
                    <div class="gv-profile-quick-actions">
                        @foreach ($profileQuickActions as $action)
                            <a href="{{ $action['href'] }}" class="gv-composer-pill gv-profile-quick-pill">
                                <strong><i class="{{ $action['icon'] }}"></i>{{ $action['label'] }}</strong>
                                <span>{{ get_phrase('Open in new workspace') }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                @isset($profileInsights['company_cards'])
                    <div class="gv-card space-y-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="gv-eyebrow mb-1">{{ get_phrase('Companies') }}</p>
                                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Pages & Organizations') }}</h3>
                            </div>
                            <a href="{{ route('pages') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Manage') }}</a>
                        </div>
                        <div class="row g-3">
                            @forelse($profileInsights['company_cards'] as $company)
                                <div class="col-md-4">
                                    <div class="gv-card space-y-2 h-100">
                                        <div class="thumbnail-133 rounded-lg" style="background-image: url('{{ $company['logo'] }}');"></div>
                                        <div class="space-y-1">
                                            <h4 class="text-base font-semibold mb-0">{{ $company['title'] }}</h4>
                                            <p class="text-sm gv-muted mb-0">{{ $company['role'] }}</p>
                                            <div class="d-flex gap-2">
                                                @foreach($company['tags'] as $tag)
                                                    <span class="gv-pill">{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="gv-muted mb-0">{{ get_phrase('No company pages linked yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                @endisset

                @isset($profileInsights['opportunity_cards'])
                    <div class="gv-card space-y-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="gv-eyebrow mb-1">{{ get_phrase('Highlights') }}</p>
                                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Featured jobs & gigs') }}</h3>
                            </div>
                            <a href="{{ Route::has('jobs.index') ? route('jobs.index') : url('/jobs') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('View more') }}</a>
                        </div>
                        <div class="row g-3">
                            @forelse($profileInsights['opportunity_cards'] as $opportunity)
                                <div class="col-md-6">
                                    <div class="gv-card space-y-1 h-100">
                                        <p class="text-sm gv-muted mb-0">{{ $opportunity['type'] }}</p>
                                        <h4 class="text-base font-semibold mb-1">{{ $opportunity['title'] }}</h4>
                                        <p class="text-sm mb-2">{{ $opportunity['company'] }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-sm gv-muted">{{ $opportunity['status'] }}</span>
                                            <a href="{{ $opportunity['link'] }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ get_phrase('Open') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="gv-muted mb-0">{{ get_phrase('No featured opportunities right now.') }}</p>
                            @endforelse
                        </div>
                    </div>
                @endisset

                @isset($profileInsights['talent_ai_cards'])
                    <div class="gv-card space-y-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="gv-eyebrow mb-1">{{ get_phrase('Talent & AI highlights') }}</p>
                                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Mandates, programmes & missions') }}</h3>
                            </div>
                        </div>
                        <div class="row g-3">
                            @forelse($profileInsights['talent_ai_cards'] as $card)
                                <div class="col-md-4">
                                    <div class="gv-card h-100 space-y-2">
                                        <span class="gv-pill">{{ ucfirst($card['type'] ?? get_phrase('Talent')) }}</span>
                                        <h4 class="text-base fw-semibold mb-0">{{ $card['title'] }}</h4>
                                        @if (!empty($card['category']))
                                            <p class="text-sm gv-muted mb-0">{{ $card['category'] }}</p>
                                        @endif
                                        @if (!empty($card['hours']))
                                            <p class="text-sm gv-muted mb-0">{{ $card['hours'] }} {{ get_phrase('hours') }}</p>
                                        @endif
                                        @if (!empty($card['meta']))
                                            <p class="text-xs gv-muted mb-0">{{ $card['meta'] }}</p>
                                        @endif
                                        @if (!empty($card['cta']))
                                            <a href="{{ $card['cta'] }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Manage') }}</a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="gv-muted mb-0">{{ get_phrase('No Talent & AI highlights yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                @endisset

                @php
                    $profileJourney = app(\App\Services\ProfileJourneyService::class)->cardsFor(auth()->user());
                @endphp
                <div class="gv-card space-y-4">
                    <div>
                        <p class="gv-eyebrow mb-1">{{ get_phrase('Journey') }}</p>
                        <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Keep your profile growing') }}</h3>
                    </div>
                    <div class="row g-3">
                        @foreach($profileJourney as $card)
                            <div class="col-md-4">
                                <div class="gv-card h-100 space-y-3">
                                    <h4 class="text-base font-semibold mb-0">{{ $card['title'] }}</h4>
                                    <ul class="list-unstyled space-y-2">
                                        @foreach($card['steps'] as $step)
                                            <li class="p-2 rounded border border-[var(--gv-color-border)]">
                                                <p class="text-sm mb-1">{{ $step['label'] }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <a href="{{ $step['link'] }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ $step['cta'] }}</a>
                                                    @if($step['completed'])
                                                        <span class="text-xs text-success"><i class="fa-solid fa-check"></i></span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <nav class="profile-nav mt-4">
                <ul class="gv-profile-tabs">
                    @php
                        $tabs = [
                            ['route' => 'profile', 'label' => get_phrase('Timeline')],
                            ['route' => 'profile.friends', 'label' => get_phrase('Friends')],
                            ['route' => 'profile.photos', 'label' => get_phrase('Photos & Reels')],
                            ['route' => 'profile.videos', 'label' => get_phrase('Videos')],
                            ['route' => 'profile.mediaHub', 'label' => get_phrase('Media Hub')],
                            ['route' => 'profile.savePostList', 'label' => get_phrase('Saved Posts')],
                        ];
                    @endphp
                    @foreach ($tabs as $tab)
                        <li>
                            <a href="{{ route($tab['route']) }}"
                                class="gv-nav-link @if (Route::currentRouteName() == $tab['route']) gv-nav-link--active @endif">
                                {{ $tab['label'] }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <div class="dropdown">
                            <button class="gv-nav-link dropdown-toggle @if (Route::currentRouteName() == 'profile.checkins_list') gv-nav-link--active @endif"
                                id="profileMore"
                                data-bs-toggle="dropdown">
                                {{ get_phrase('More') }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="profileMore">
                                <li>
                                    <a class="dropdown-item @if (Route::currentRouteName() == 'profile.checkins_list') active @endif"
                                        href="{{ route('profile.checkins_list') }}">
                                        {{ get_phrase('Check-ins') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>

        @if(config('advertisement.enabled'))
            @php($profileAd = app(\App\Services\AdvertisementSurfaceService::class)->forSlot('profile'))
            @includeWhen($profileAd, 'advertisement::components.ad_banner', ['ad' => $profileAd])
        @endif
    </div>
    <div class="profile-content mt-3">
        <div class="row gx-4">
            <div class="col-12 col-xl-8">
                @if (Route::currentRouteName() == 'profile.friends')
                    @include('frontend.profile.friends')
                @elseif(Route::currentRouteName() == 'profile.photos')
                    @include('frontend.profile.photos')
                    
                @elseif(Route::currentRouteName() == 'album.details.list')
                    @include('frontend.profile.single_album_list_details')

                @elseif(Route::currentRouteName() == 'profile.videos')
                    @include('frontend.profile.videos')

                @elseif(Route::currentRouteName() == 'profile.mediaHub')
                    @include('frontend.profile.media_hub')

                @elseif(Route::currentRouteName() == 'profile.savePostList')
                    @include('frontend.profile.savePostList')

                @elseif(Route::currentRouteName() == 'profile.checkins_list')
                    @include('frontend.profile.checkins_list')

                @else
                    @include('frontend.main_content.create_post')

                    <div id="profile-timeline-posts">
                        @include('frontend.main_content.posts', ['type' => 'user_post'])
                    </div>

                    @include('frontend.main_content.scripts')
                @endif
            </div>
            <div class="col-12 col-xl-4">
                @include('frontend.profile.profile_info', [
                    'type' => 'my_account',
                    'profileInsights' => $profileInsights ?? [],
                ])
            </div>
        </div>
    </div>
    <!-- Profile content End -->
</div>

@include('frontend.profile.scripts')
