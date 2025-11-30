@php
    $jobs = $recommendations['jobs'] ?? [];
    $freelance = $recommendations['freelance'] ?? [];
    $liveSessions = $recommendations['live'] ?? [];
    $utilities = $recommendations['utilities'] ?? [];
    $talentAi = $recommendations['talent_ai'] ?? [];
    $hasLanes = collect([$jobs, $freelance, $liveSessions, $utilities, $talentAi])->filter(fn($lane) => !empty($lane))->isNotEmpty() || !empty($laneAd);
@endphp

@if ($hasLanes)
    <div class="gv-card gv-feed-card gv-feed-recos">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Cross-addon spotlight') }}</p>
                <h3 class="gv-heading text-lg mb-0">
                    {{ get_phrase('Jobs, gigs, live sessions & utilities picked for you') }}
                </h3>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('jobs') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Jobs hub') }}</a>
                <a href="{{ Route::has('freelance.dashboard') ? route('freelance.dashboard') : url('/freelance/dashboard') }}"
                    class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Freelance hub') }}</a>
            </div>
        </div>

        <div class="gv-feed-recos__grid">
            @if (!empty($jobs))
                <section class="gv-feed-recos__section">
                    <div class="gv-feed-eyebrow">
                        <i class="fa-solid fa-briefcase"></i>
                        {{ get_phrase('Recommended roles') }}
                    </div>
                    <div class="gv-feed-recos__items">
                        @foreach ($jobs as $job)
                            <div class="gv-feed-recos__item">
                                <p class="gv-feed-recos__item-title">{{ $job['title'] }}</p>
                                <div class="gv-feed-recos__item-meta">
                                    <span>{{ $job['company'] }}</span>
                                    @if (!empty($job['posted_at']))
                                        <span>{{ $job['posted_at'] }}</span>
                                    @endif
                                </div>
                                <div class="gv-feed-item-cta">
                                    <a href="{{ $job['link'] }}">
                                        {{ get_phrase('View role') }} <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (!empty($talentAi))
                @foreach ($talentAi as $section)
                    <section class="gv-feed-recos__section">
                        <div class="gv-feed-eyebrow">
                            <i class="{{ $section['icon'] ?? 'fa-solid fa-sparkles' }}"></i>
                            {{ $section['label'] ?? get_phrase('Talent & AI') }}
                        </div>
                        <div class="gv-feed-recos__items">
                            @foreach ($section['items'] as $item)
                                <div class="gv-feed-recos__item">
                                    <p class="gv-feed-recos__item-title">{{ $item['title'] }}</p>
                                    @if (!empty($item['subtitle']) || !empty($item['meta']))
                                        <div class="gv-feed-recos__item-meta">
                                            @if (!empty($item['subtitle']))
                                                <span>{{ $item['subtitle'] }}</span>
                                            @endif
                                            @if (!empty($item['meta']))
                                                <span>{{ $item['meta'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="gv-feed-item-cta">
                                        @if (!empty($item['link']))
                                            <a href="{{ $item['link'] }}">
                                                {{ get_phrase('Open workspace') }} <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        @endif
                                        @if (!empty($item['secondary_link']))
                                            <a href="{{ $item['secondary_link'] }}" class="ms-3 text-sm">
                                                {{ get_phrase('Related jobs') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            @endif

            @if (!empty($freelance))
                <section class="gv-feed-recos__section">
                    <div class="gv-feed-eyebrow">
                        <i class="fa-solid fa-handshake-angle"></i>
                        {{ get_phrase('Freelance & gigs') }}
                    </div>
                    <div class="gv-feed-recos__items">
                        @foreach ($freelance as $gig)
                            <div class="gv-feed-recos__item">
                                <p class="gv-feed-recos__item-title">{{ $gig['title'] }}</p>
                                <div class="gv-feed-recos__item-meta">
                                    <span>{{ $gig['owner'] }}</span>
                                    <span>{{ $gig['type'] }}</span>
                                </div>
                                <div class="gv-feed-item-cta">
                                    <a href="{{ $gig['link'] }}">
                                        {{ get_phrase('View brief') }} <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (!empty($liveSessions))
                <section class="gv-feed-recos__section">
                    <div class="gv-feed-eyebrow">
                        <i class="fa-solid fa-podcast"></i>
                        {{ get_phrase('Live & interactive') }}
                    </div>
                    <div class="gv-feed-recos__items">
                        @foreach ($liveSessions as $session)
                            <div class="gv-feed-recos__item">
                                <p class="gv-feed-recos__item-title">{{ $session['title'] }}</p>
                                <div class="gv-feed-recos__item-meta">
                                    <span>{{ $session['location'] }}</span>
                                    @if (!empty($session['time']))
                                        <span>{{ $session['time'] }}</span>
                                    @endif
                                </div>
                                <div class="gv-feed-item-cta">
                                    <a href="{{ $session['link'] }}">
                                        {{ get_phrase('Open session') }} <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (!empty($utilities))
                <section class="gv-feed-recos__section">
                    <div class="gv-feed-eyebrow">
                        <i class="fa-solid fa-bolt"></i>
                        {{ get_phrase('Utilities quick tools') }}
                    </div>
                    <div class="gv-feed-recos__items">
                        @foreach ($utilities as $utility)
                            <a href="{{ $utility['url'] }}" class="gv-feed-recos__item">
                                <p class="gv-feed-recos__item-title">
                                    <i class="{{ $utility['icon'] }} me-2"></i>{{ $utility['label'] }}
                                </p>
                                <span class="gv-feed-item-summary mb-0">{{ get_phrase('Launch module') }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (!empty($laneAd))
                <section class="gv-feed-recos__section">
                    <div class="gv-feed-eyebrow">
                        <i class="fa-solid fa-bullhorn"></i>
                        {{ get_phrase('Sponsored') }}
                    </div>
                    <div class="gv-feed-recos__ad">
                        @include('advertisement::components.ad_feed_card', ['ad' => $laneAd])
                    </div>
                </section>
            @endif
        </div>
    </div>
@endif

