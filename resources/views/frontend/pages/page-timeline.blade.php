@php
    $section = $activeSection ?? 'feed';
    $panels = $pagePanels ?? [];
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
    $sectionRoute = function (string $key) use ($page) {
        return route('single.page', ['id' => $page->id, 'section' => $key]);
    };
    $isOwner = $page->user_id == auth()->user()->id;
@endphp

<div class="gv-shell">
    <div class="gv-shell-header">
        <div class="gv-shell-header-inner">
            <div class="d-flex align-items-center gap-4 w-100">
                <div class="gv-profile-avatar-lg gv-avatar--photo">
                    <div class="gv-profile-avatar-inner">
                        <img src="{{ get_page_logo($page->logo, 'logo') }}" alt="{{ $page->title }}">
                    </div>
                </div>
                <div class="flex-grow-1">
                    <span class="gv-pill-page-label">
                        <span class="gv-pill-page-label-dot"></span>
                        {{ get_phrase('Company Page') }}
                    </span>
                    <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
                        <div>
                            <h1 class="gv-main-heading mb-0">{{ $page->title }}</h1>
                            <p class="gv-main-heading-sub mb-0">
                                {{ optional($page->getCategory)->name ?? get_phrase('Industry') }} ·
                                {{ $page->location ?? get_phrase('Global') }}
                            </p>
                        </div>
                        <div class="ms-auto d-flex flex-wrap gap-2">
                            @if ($isOwner)
                                <button class="gv-btn gv-btn-ghost"
                                    onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.pages.edit-modal', 'page_id' => $page->id]) }}', '{{ get_phrase('Edit Page') }}');">
                                    <i class="fa-solid fa-pen"></i> {{ get_phrase('Edit Page') }}
                                </button>
                                <button class="gv-btn gv-btn-ghost"
                                    onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.pages.edit-cover-photo', 'page_id' => $page->id]) }}', '{{ get_phrase('Update cover photo') }}');">
                                    <i class="fa-solid fa-camera"></i> {{ get_phrase('Cover Photo') }}
                                </button>
                            @endif
                            @if ($hasLikedPage ?? false)
                                <button class="gv-btn gv-btn-primary"
                                    onclick="ajaxAction('{{ route('page.dislike', $page->id) }}')">
                                    <i class="fa-solid fa-thumbs-up"></i> {{ get_phrase('Liked') }}
                                </button>
                            @else
                                <button class="gv-btn gv-btn-primary"
                                    onclick="ajaxAction('{{ route('page.like', $page->id) }}')">
                                    <i class="fa-regular fa-thumbs-up"></i> {{ get_phrase('Like Page') }}
                                </button>
                            @endif
                            <a href="{{ route('pages') }}" class="gv-btn gv-btn-ghost">
                                <i class="fa-solid fa-layer-group"></i> {{ get_phrase('All Pages') }}
                            </a>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        <div class="gv-pill">
                            <strong>{{ number_format($stats['followers'] ?? 0) }}</strong>
                            <span>{{ get_phrase('Followers') }}</span>
                        </div>
                        <div class="gv-pill">
                            <strong>{{ number_format($stats['posts'] ?? 0) }}</strong>
                            <span>{{ get_phrase('Posts') }}</span>
                        </div>
                        <div class="gv-pill">
                            <strong>{{ number_format($stats['jobs'] ?? 0) }}</strong>
                            <span>{{ get_phrase('Jobs') }}</span>
                        </div>
                        <div class="gv-pill">
                            <strong>{{ number_format($stats['events'] ?? 0) }}</strong>
                            <span>{{ get_phrase('Events') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="gv-shell-main">
        <div class="gv-shell-grid">
            <aside class="gv-sidebar space-y-4">
                <div class="gv-sidebar-section-title">{{ get_phrase('About this page') }}</div>
                <div class="gv-card space-y-3">
                    <p class="gv-body mb-0">{!! nl2br(e($page->description ?? get_phrase('No description added yet.'))) !!}</p>
                    <hr class="gv-divider">
                    <div class="d-flex flex-column gap-2">
                        <span class="gv-muted"><i class="fa-solid fa-location-dot me-2"></i>{{ $page->location ?? get_phrase('Global reach') }}</span>
                        <span class="gv-muted"><i class="fa-solid fa-briefcase me-2"></i>{{ optional($page->getCategory)->name ?? get_phrase('Industry TBD') }}</span>
                    </div>
                </div>
                @include('components.utilities.quick-tools', ['context' => 'page'])
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
                        <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Mission & Highlights') }}</h3>
                        <p class="gv-body">{!! nl2br(e($page->description ?? get_phrase('No mission statement yet.'))) !!}</p>
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
                                <p class="gv-eyebrow mb-1">{{ get_phrase('Open roles') }}</p>
                                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Jobs & freelance briefs') }}</h3>
                            </div>
                            <a href="{{ Route::has('create.job') ? route('create.job') : route('jobs') }}" class="gv-btn gv-btn-primary">
                                {{ get_phrase('Create job') }}
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
                                <p class="gv-muted mb-0">{{ get_phrase('No active roles published yet.') }}</p>
                            @endforelse
                        </div>
                    </div>
                @elseif ($section === 'events')
                    <div class="gv-card space-y-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="gv-eyebrow mb-1">{{ get_phrase('Engagements') }}</p>
                                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Events & live moments') }}</h3>
                            </div>
                            <a href="{{ Route::has('liveCenter.hub') ? route('liveCenter.hub') : route('event') }}" class="gv-btn gv-btn-primary">
                                {{ get_phrase('Open Live Center') }}
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
                        <p class="gv-eyebrow mb-1">{{ get_phrase('Performance overview') }}</p>
                        <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Page analytics snapshot') }}</h3>
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
                    @if ($isOwner)
                        @include('frontend.main_content.create_post', ['page_id' => $page->id])
                    @endif
                    @php
                        $comments = DB::table('comments')
                            ->join('users', 'comments.user_id', '=', 'users.id')
                            ->where('comments.is_type', 'page')
                            ->where('comments.id_of_type', $page->id)
                            ->where('comments.parent_id', 0)
                            ->select('comments.*', 'users.name', 'users.photo')
                            ->orderBy('comment_id', 'DESC')
                            ->take(1)
                            ->get();
                    @endphp
                    @include('frontend.main_content.comments', ['comments' => $comments, 'post_id' => $page->id, 'type' => 'page'])
                    @include('frontend.main_content.posts', ['type' => 'page'])
                @endif
            </section>

            <aside class="gv-sidebar space-y-4">
                <div class="gv-card space-y-3">
                    <h4 class="gv-heading text-base mb-0">{{ get_phrase('Key analytics') }}</h4>
                    <div class="space-y-2">
                        @foreach ($analytics as $metric)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="gv-muted">{{ $metric['label'] ?? '' }}</span>
                                <strong>{{ $metric['value'] ?? '—' }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if($pageAd ?? false)
                    @include('advertisement::components.feed_card', ['ad' => $pageAd])
                @endif
                <div class="gv-card space-y-3">
                    <h4 class="gv-heading text-base mb-0">{{ get_phrase('Need a boost?') }}</h4>
                    <p class="gv-body mb-0">{{ get_phrase('Use Utilities to schedule announcements, reminders, and reporting for this page.') }}</p>
                    <a href="{{ Route::has('utilities.analytics') ? route('utilities.analytics') : url('/utilities/hub') }}" class="gv-btn gv-btn-primary w-100">
                        {{ get_phrase('Open Utilities') }}
                    </a>
                </div>
            </aside>
        </div>
    </div>
</div>

@if ($section === 'feed')
    @include('frontend.main_content.scripts')
@endif
        