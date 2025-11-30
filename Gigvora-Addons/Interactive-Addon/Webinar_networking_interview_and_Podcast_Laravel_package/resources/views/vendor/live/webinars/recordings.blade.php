@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Replays') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Webinar recordings') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Catch up with recent sessions, highlights, and chaptered replays.') }}</p>
    </div>
@endsection

@section('live-content')
    @php
        $recordings = $recordings ?? [
            ['title' => 'Designing with AI', 'date' => 'Mar 3', 'duration' => '54m', 'tags' => 'Design, AI'],
            ['title' => 'Scaling APIs', 'date' => 'Mar 10', 'duration' => '48m', 'tags' => 'Backend'],
        ];
    @endphp
    <div class="space-y-4">
        <div class="gv-card space-y-3">
            <div class="grid gap-4 md:grid-cols-3">
                <label class="space-y-1">
                    <span class="gv-label">{{ get_phrase('Search recordings') }}</span>
                    <input type="search" class="gv-input" name="search" placeholder="{{ get_phrase('Title or topic') }}">
                </label>
                <label class="space-y-1">
                    <span class="gv-label">{{ get_phrase('Category') }}</span>
                    <select class="gv-input" name="category">
                        <option value="">{{ get_phrase('All categories') }}</option>
                        <option>{{ get_phrase('Engineering') }}</option>
                        <option>{{ get_phrase('Product') }}</option>
                    </select>
                </label>
            </div>
        </div>

        <div class="space-y-2">
            @forelse($recordings as $recording)
                <div class="gv-card flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                            {{ $recording['title'] }}
                        </p>
                        <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                            {{ $recording['date'] }} • {{ $recording['duration'] }} • {{ $recording['tags'] }}
                        </p>
                    </div>
                    <a class="gv-btn gv-btn-ghost"
                        href="{{ route('wnip.webinars.recording', ['recording' => $loop->index + 1]) ?? '#' }}">
                        {{ get_phrase('Watch replay') }}
                    </a>
                </div>
            @empty
                <div class="gv-empty">
                    {{ get_phrase('No recordings available yet.') }}
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
