<div class="space-y-4">
    <div class="gv-card">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-4">
            <div>
                <p class="text-uppercase text-muted fw-semibold mb-1">{{ get_phrase('Utilities') }}</p>
                <h1 class="h3 mb-2">{{ get_phrase('Calendar & reminders') }}</h1>
                <p class="text-muted mb-0">
                    {{ get_phrase('Review upcoming interviews, events, and invites synced from Jobs, Freelance, Interactive, and core social flows.') }}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-start">
                <a href="{{ route('notifications.index') }}" class="gv-btn gv-btn-ghost">
                    <i class="fa-regular fa-bell me-1"></i> {{ get_phrase('Notifications') }}
                </a>
                <a href="{{ route('saved.index') }}" class="gv-btn gv-btn-ghost">
                    <i class="fa-regular fa-bookmark me-1"></i> {{ get_phrase('Saved items') }}
                </a>
                <a href="{{ route('utilities.hub') }}" class="gv-btn gv-btn-primary">
                    <i class="fa-solid fa-bolt me-1"></i> {{ get_phrase('Utilities hub') }}
                </a>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-4 mt-4">
            <div>
                <p class="text-muted text-uppercase small mb-1">{{ get_phrase('Events hosted') }}</p>
                <h3 class="h2 mb-0">{{ number_format($insights['hosted']) }}</h3>
            </div>
            <div>
                <p class="text-muted text-uppercase small mb-1">{{ get_phrase('Invites awaiting response') }}</p>
                <h3 class="h2 mb-0">{{ number_format($insights['invited']) }}</h3>
            </div>
            <div>
                <p class="text-muted text-uppercase small mb-1">{{ get_phrase('Interviews scheduled') }}</p>
                <h3 class="h2 mb-0">{{ number_format($insights['interviews']) }}</h3>
            </div>
            <div>
                <p class="text-muted text-uppercase small mb-1">{{ get_phrase('Reminders pending') }}</p>
                <h3 class="h2 mb-0">{{ number_format($insights['pendingReminders']) }}</h3>
            </div>
        </div>
    </div>

    <div class="gv-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">{{ get_phrase('Upcoming timeline') }}</h2>
            <span class="badge bg-primary text-white">{{ number_format($timeline->count()) }}</span>
        </div>
        <div class="d-flex flex-column gap-3">
            @forelse($timeline as $entry)
                <div class="border rounded-3 p-3 d-flex flex-column flex-md-row gap-3">
                    <div class="text-center text-md-start" style="min-width: 120px;">
                        <p class="mb-0 fw-semibold">{{ $entry['date']->format('M d') }}</p>
                        <small class="text-muted">{{ $entry['date']->format('H:i') }}</small>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-1 fw-semibold">{{ $entry['title'] }}</p>
                        <p class="text-muted mb-2">
                            <i class="fa-solid fa-location-dot me-1"></i>{{ $entry['location'] }}
                            ·
                            {{ $entry['context_label'] ?? get_phrase('Utilities event') }}
                        </p>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            @if(!empty($entry['status_label']))
                                <span class="badge text-bg-light text-muted">{{ $entry['status_label'] }}</span>
                            @endif
                            @if(!empty($entry['link']))
                                <a href="{{ $entry['link'] }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                                    {{ get_phrase('Open details') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">{{ get_phrase('No upcoming items yet. Book interviews, register for events, or create reminders to see them here.') }}</p>
            @endforelse
        </div>
    </div>

    <div class="gv-card">
        <h3 class="h6 mb-3">{{ get_phrase('Cross-addon quick links') }}</h3>
        <div class="d-flex flex-wrap gap-2">
            @if($integrationLinks['jobsSaved'])
                <a href="{{ $integrationLinks['jobsSaved'] }}" class="gv-chip">
                    <i class="fa-solid fa-briefcase me-2"></i> {{ get_phrase('Saved jobs') }}
                </a>
            @endif
            @if($integrationLinks['freelanceDashboard'])
                <a href="{{ $integrationLinks['freelanceDashboard'] }}" class="gv-chip">
                    <i class="fa-solid fa-handshake-angle me-2"></i> {{ get_phrase('Freelance dashboard') }}
                </a>
            @endif
            @if($integrationLinks['liveHub'])
                <a href="{{ $integrationLinks['liveHub'] }}" class="gv-chip">
                    <i class="fa-solid fa-broadcast-tower me-2"></i> {{ get_phrase('Live & events') }}
                </a>
            @endif
            @if($integrationLinks['utilitiesHub'])
                <a href="{{ $integrationLinks['utilitiesHub'] }}" class="gv-chip">
                    <i class="fa-solid fa-bolt me-2"></i> {{ get_phrase('Utilities hub') }}
                </a>
            @endif
        </div>
    </div>
</div>

