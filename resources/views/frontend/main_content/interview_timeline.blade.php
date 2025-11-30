<div class="gv-card gv-feed-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-muted fw-semibold mb-1">{{ get_phrase('Utilities') }}</p>
            <h2 class="h5 mb-0">{{ get_phrase('Upcoming interviews') }}</h2>
        </div>
        @if (Route::has('utilities.calendar.index'))
            <a href="{{ route('utilities.calendar.index') }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                {{ get_phrase('Open calendar') }}
            </a>
        @endif
    </div>
    <div class="d-flex flex-column gap-3">
        @foreach($entries as $entry)
            <div class="d-flex justify-content-between align-items-start flex-column flex-md-row">
                <div>
                    <p class="fw-semibold mb-1">{{ $entry['title'] }}</p>
                    <p class="text-muted mb-1">{{ $entry['subtitle'] }}</p>
                    <p class="text-xs text-uppercase text-muted mb-0">
                        {{ $entry['date']->format('M d · H:i') }} · {{ ucfirst($entry['status']) }}
                    </p>
                </div>
                @if (!empty($entry['cta_url']))
                    <a href="{{ $entry['cta_url'] }}" class="gv-btn gv-btn-ghost gv-btn-sm mt-2 mt-md-0">
                        {{ $entry['cta_label'] }}
                    </a>
                @endif
            </div>
        @endforeach
    </div>
</div>

