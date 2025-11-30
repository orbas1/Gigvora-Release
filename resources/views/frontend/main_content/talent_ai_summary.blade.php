@if (!empty($summary))
    <section class="gv-card gv-feed-card gv-talent-ai">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Talent & AI intelligence') }}</p>
                <h3 class="gv-heading text-lg mb-0">
                    {{ get_phrase('Headhunters, Launchpad, Volunteering & AI workspace signals') }}
                </h3>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('addons.talent_ai.headhunters.dashboard') }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                    <i class="fa-solid fa-user-tie me-1"></i>{{ get_phrase('Open dashboard') }}
                </a>
                <a href="{{ route('addons.talent_ai.ai_workspace.index') }}" class="gv-btn gv-btn-primary gv-btn-sm">
                    <i class="fa-solid fa-robot me-1"></i>{{ get_phrase('AI workspace') }}
                </a>
            </div>
        </div>

        @if (!empty($summary['stats']))
            <div class="gv-talent-ai__stats mb-4">
                @foreach ($summary['stats'] as $stat)
                    <div class="gv-talent-ai__stat">
                        <p class="text-sm gv-muted mb-1">{{ $stat['label'] }}</p>
                        <p class="gv-heading text-2xl mb-1">
                            {{ $stat['value'] }}
                            @if (!empty($stat['suffix']))
                                <span class="text-base fw-normal">{{ $stat['suffix'] }}</span>
                            @endif
                        </p>
                        <p class="text-xs gv-muted mb-2">{{ $stat['description'] }}</p>
                        @if (!empty($stat['href']))
                            <a href="{{ $stat['href'] }}" class="gv-link">{{ get_phrase('Review details') }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $cards = $summary['cards'] ?? [];
        @endphp

        @if (!empty(array_filter($cards)))
            <div class="gv-talent-ai__cards">
                @if (!empty($cards['launchpad']))
                    <div class="gv-talent-ai__card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="gv-pill"><i class="fa-solid fa-graduation-cap me-1"></i>{{ get_phrase('Launchpad') }}</span>
                            @if (!empty($cards['launchpad']['link']))
                                <a href="{{ $cards['launchpad']['link'] }}" class="gv-link text-sm">{{ get_phrase('Manage') }}</a>
                            @endif
                        </div>
                        <h4 class="text-base fw-semibold mb-1">{{ $cards['launchpad']['title'] }}</h4>
                        <p class="text-sm gv-muted mb-3">{{ ucfirst($cards['launchpad']['category'] ?? get_phrase('In review')) }}</p>
                        @if (!empty($cards['launchpad']['progress']))
                            @php
                                $progress = $cards['launchpad']['progress'];
                            @endphp
                            <div class="gv-progress mb-1">
                                <span style="width: {{ $progress['percent'] ?? 0 }}%;"></span>
                            </div>
                            <p class="text-xs gv-muted mb-0">
                                {{ get_phrase('Completed') }} {{ $progress['completed'] }}/{{ $progress['total'] ?? 0 }}
                                ({{ $progress['percent'] ?? 0 }}%)
                            </p>
                        @endif
                    </div>
                @endif

                @if (!empty($cards['volunteering']))
                    <div class="gv-talent-ai__card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="gv-pill gv-pill--success"><i class="fa-solid fa-hand-holding-heart me-1"></i>{{ get_phrase('Volunteering') }}</span>
                            @if (!empty($cards['volunteering']['link']))
                                <a href="{{ $cards['volunteering']['link'] }}" class="gv-link text-sm">{{ get_phrase('Opportunities') }}</a>
                            @endif
                        </div>
                        <h4 class="text-base fw-semibold mb-1">{{ get_phrase('Verified hours') }}</h4>
                        <p class="display-6 mb-1">{{ $cards['volunteering']['hours'] ?? 0 }}h</p>
                        <p class="text-sm gv-muted mb-0">{{ get_phrase('Applications') }}: {{ $cards['volunteering']['applications'] ?? 0 }}</p>
                    </div>
                @endif

                @if (!empty($cards['ai']))
                    <div class="gv-talent-ai__card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="gv-pill"><i class="fa-solid fa-robot me-1"></i>{{ get_phrase('AI workspace') }}</span>
                            @if (!empty($cards['ai']['link']))
                                <a href="{{ $cards['ai']['link'] }}" class="gv-link text-sm">{{ get_phrase('Launch') }}</a>
                            @endif
                        </div>
                        <h4 class="text-base fw-semibold mb-2">{{ get_phrase('Sessions this week') }}</h4>
                        <p class="display-6 mb-1">{{ $cards['ai']['sessions_this_week'] ?? 0 }}</p>
                        <p class="text-sm gv-muted mb-0">
                            {{ get_phrase('Last tool') }}:
                            {{ ucfirst(str_replace('_', ' ', $cards['ai']['last_tool'] ?? get_phrase('N/A'))) }}
                            @if (!empty($cards['ai']['last_run']))
                                · {{ $cards['ai']['last_run'] }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        @endif
    </section>
@endif

