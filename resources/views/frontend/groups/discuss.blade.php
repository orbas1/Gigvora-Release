@php
    $section = $activeSection ?? 'feed';
    $panels = $groupPanels ?? [];
    $stats = $panels['stats'] ?? [];
    $jobs = $panels['jobs'] ?? [];
    $gigs = $panels['gigs'] ?? [];
    $events = $panels['events'] ?? [];
    $analytics = $panels['analytics'] ?? [];
    $navItems = [
        'about' => get_phrase('About'),
        'feed' => get_phrase('Feed'),
        'jobs' => get_phrase('Jobs'),
        'events' => get_phrase('Events'),
        'analytics' => get_phrase('Analytics'),
    ];
    $sectionRoute = function (string $key) use ($group) {
        return route('single.group', ['id' => $group->id, 'section' => $key]);
    };
    $isOwner = $group->user_id == auth()->user()->id;
    $isMember = ($hasJoinedGroup ?? false) || $isOwner;
@endphp

<div class="gv-shell">
    <div class="gv-shell-header">
        <div class="gv-shell-header-inner">
            <div class="d-flex align-items-center gap-4 w-100">
                <div class="gv-profile-avatar-lg gv-avatar--photo">
                    <div class="gv-profile-avatar-inner">
                        <img src="{{ get_group_logo($group->logo, 'logo') }}" alt="{{ $group->title }}">
                    </div>
                </div>
                <div class="flex-grow-1">
                    <span class="gv-pill-page-label">
                        <span class="gv-pill-page-label-dot"></span>
                        {{ get_phrase('Community') }}
                    </span>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
                        <div>
                            <h1 class="gv-main-heading mb-0">{{ $group->title }}</h1>
                            <p class="gv-main-heading-sub mb-0">
                                {{ $group->subtitle ?? get_phrase('No tagline yet') }} · {{ ucfirst($group->privacy) }}
                            </p>
                        </div>
                        <div class="ms-auto d-flex flex-wrap gap-2">
                            @if ($isOwner)
                                <button class="gv-btn gv-btn-ghost"
                                    onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.groups.edit-modal', 'group_id' => $group->id]) }}', '{{ get_phrase('Edit Group') }}');">
                                    <i class="fa-solid fa-pen"></i> {{ get_phrase('Edit') }}
                                </button>
                                <button class="gv-btn gv-btn-ghost"
                                    onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.groups.edit-cover-photo', 'group_id' => $group->id]) }}', '{{ get_phrase('Update cover photo') }}');">
                                    <i class="fa-solid fa-camera"></i> {{ get_phrase('Cover Photo') }}
                                </button>
                            @endif
                            @if ($isMember)
                                <button class="gv-btn gv-btn-primary"
                                    onclick="ajaxAction('{{ route('group.rjoin', $group->id) }}')">
                                    <i class="fa-solid fa-user-check"></i> {{ get_phrase('Joined') }}
                                </button>
                            @else
                                <button class="gv-btn gv-btn-primary"
                                    onclick="ajaxAction('{{ route('group.join', $group->id) }}')">
                                    <i class="fa-solid fa-user-plus"></i> {{ get_phrase('Join Group') }}
                                </button>
                            @endif
                            <a href="#" class="gv-btn gv-btn-ghost" data-bs-toggle="modal" data-bs-target="#newGroup">
                                <i class="fa-solid fa-paper-plane"></i> {{ get_phrase('Invite') }}
                            </a>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        <div class="gv-pill">
                            <strong>{{ number_format($stats['members'] ?? $membercount ?? 0) }}</strong>
                            <span>{{ get_phrase('Members') }}</span>
                        </div>
                        <div class="gv-pill">
                            <strong>{{ number_format($stats['posts'] ?? 0) }}</strong>
                            <span>{{ get_phrase('Posts') }}</span>
                        </div>
                        <div class="gv-pill">
                            <strong>{{ number_format($stats['jobs'] ?? 0) }}</strong>
                            <span>{{ get_phrase('Jobs & gigs') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="gv-shell-main">
        <div class="gv-shell-grid">
            <aside class="gv-sidebar space-y-4">
                <div class="gv-sidebar-section-title">{{ get_phrase('Group details') }}</div>
                <div class="gv-card space-y-3">
                    <p class="gv-body mb-0">{!! nl2br(e($group->about ?? get_phrase('No description yet.'))) !!}</p>
                    <hr class="gv-divider">
                    <div class="d-flex flex-column gap-2">
                        <span class="gv-muted"><i class="fa-solid fa-shield-halved me-2"></i>{{ ucfirst($group->privacy) }}</span>
                        <span class="gv-muted"><i class="fa-solid fa-map-pin me-2"></i>{{ $group->location ?? get_phrase('Global community') }}</span>
                    </div>
                </div>
                @include('components.utilities.quick-tools', ['context' => 'group'])
            </aside>

            <section class="gv-main space-y-4">
                <div class="gv-card p-3">
                    <ul class="gv-profile-tabs">
                        @foreach ($navItems as $key => $label)
                            <li>
                                <a href="{{ $sectionRoute($key) }}"
                                    class="gv-nav-link {{ $section === $key ? 'gv-nav-link--active' : '' }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if ($section === 'about')
                    <div class="gv-card space-y-3">
                        <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Community overview') }}</h3>
                        <p class="gv-body">{!! nl2br(e($group->about ?? get_phrase('Introduce this group to attract more members.'))) !!}</p>
                        <div class="row g-3">
                            @foreach ($analytics as $card)
                                <div class="col-md-6">
                                    <div class="gv-card space-y-1 h-100">
                                        <p class="gv-muted mb-0">{{ $card['label'] ?? '' }}</p>
                                        <h4 class="gv-heading text-xl mb-0">{{ $card['value'] ?? '—' }}</h4>
                                        <p class="gv-muted mb-0">{{ $card['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif ($section === 'jobs')
                    <div class="gv-card space-y-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="gv-eyebrow mb-1">{{ get_phrase('Opportunities') }}</p>
                                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Jobs & gigs shared here') }}</h3>
                            </div>
                            <a href="{{ Route::has('create.job') ? route('create.job') : route('jobs') }}" class="gv-btn gv-btn-primary">
                                {{ get_phrase('Post job') }}
                            </a>
                        </div>
                        <div class="space-y-3">
                            @forelse (array_merge($jobs, $gigs) as $job)
                                <div class="gv-card space-y-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="gv-eyebrow mb-1">{{ $job['type'] ?? '' }}</p>
                                            <h4 class="gv-heading text-base mb-0">{{ $job['title'] ?? '' }}</h4>
                                        </div>
                                        <span class="gv-muted">{{ $job['meta'] ?? '' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ $job['link'] ?? '#' }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                                            {{ get_phrase('View post') }}
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="gv-muted mb-0">{{ get_phrase('No active opportunities in this group yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                @elseif ($section === 'events')
                    <div class="gv-card space-y-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="gv-eyebrow mb-1">{{ get_phrase('Live moments') }}</p>
                                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Group events & networking') }}</h3>
                            </div>
                            <a href="{{ Route::has('event') ? route('event') : url('/events') }}" class="gv-btn gv-btn-primary">
                                {{ get_phrase('Create event') }}
                            </a>
                        </div>
                        <div class="space-y-3">
                            @forelse ($events as $event)
                                <div class="gv-card space-y-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="gv-eyebrow mb-1">{{ $event['type'] ?? get_phrase('Event') }}</p>
                                            <h4 class="gv-heading text-base mb-0">{{ $event['title'] ?? '' }}</h4>
                                        </div>
                                        <span class="gv-muted">{{ $event['meta'] ?? '' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ $event['link'] ?? '#' }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                                            {{ get_phrase('Preview') }}
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="gv-muted mb-0">{{ get_phrase('No upcoming events shared yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                @elseif ($section === 'analytics')
                    <div class="gv-card space-y-3">
                        <p class="gv-eyebrow mb-1">{{ get_phrase('Community health') }}</p>
                        <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Engagement snapshot') }}</h3>
                        <div class="row g-3">
                            @foreach ($analytics as $metric)
                                <div class="col-md-6">
                                    <div class="gv-card space-y-1 h-100">
                                        <p class="gv-muted mb-0">{{ $metric['label'] ?? '' }}</p>
                                        <h4 class="gv-heading text-2xl mb-0">{{ $metric['value'] ?? '—' }}</h4>
                                        <p class="gv-muted mb-0">{{ $metric['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    @if ($isMember)
                        @include('frontend.main_content.create_post', ['group_id' => $group->id])
                        @include('frontend.main_content.posts', ['type' => 'group'])
                    @else
                        <div class="gv-card">
                            <h4 class="gv-heading text-base mb-2">{{ get_phrase('Join to participate') }}</h4>
                            <p class="gv-body mb-0">{{ get_phrase('This group’s feed is visible once you become a member.') }}</p>
                        </div>
                    @endif
                @endif
            </section>

            <aside class="gv-sidebar space-y-4">
                <div class="gv-card space-y-3">
                    <h4 class="gv-heading text-base mb-0">{{ get_phrase('Insights') }}</h4>
                    <div class="space-y-2">
                        @foreach ($analytics as $metric)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="gv-muted">{{ $metric['label'] ?? '' }}</span>
                                <strong>{{ $metric['value'] ?? '—' }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if($groupAd ?? false)
                    @include('advertisement::components.feed_card', ['ad' => $groupAd])
                @endif
                <div class="gv-card space-y-3">
                    <h4 class="gv-heading text-base mb-0">{{ get_phrase('Moderation toolkit') }}</h4>
                    <p class="gv-body mb-0">{{ get_phrase('Use Utilities moderation + reminders to keep discussions healthy.') }}</p>
                    <a href="{{ Route::has('utilities.moderation') ? route('utilities.moderation') : url('/utilities/hub') }}" class="gv-btn gv-btn-primary w-100">
                        {{ get_phrase('Open Utilities') }}
                    </a>
                </div>
            </aside>
        </div>
    </div>
</div>

@include('frontend.groups.invite')
@if ($section === 'feed' && $isMember)
    @include('frontend.main_content.scripts')
@endif
