@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Replay') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Watch webinar replay') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Control playback speed, jump to chapters, and review resources.') }}</p>
    </div>
@endsection

@section('live-content')
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_360px]" id="recording-player">
        <div class="space-y-4">
            <div class="gv-card p-0 overflow-hidden">
                <div class="bg-[var(--gv-color-neutral-900)] text-white flex items-center justify-center min-h-[360px]"
                    id="recording-video">
                    {{ get_phrase('Recording player') }}
                </div>
                <div class="border-t border-[var(--gv-color-border)] p-4 flex flex-wrap gap-2">
                    @foreach ([0.75, 1, 1.25, 1.5] as $speed)
                        <button class="gv-btn gv-btn-ghost text-xs py-1 px-3" data-speed="{{ $speed }}">
                            {{ $speed }}x
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="gv-card space-y-3">
                <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Chapters') }}</h3>
                <ul class="space-y-2 text-sm" id="recording-chapters">
                    <li><a href="#" data-seek="0" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Intro (0:00)') }}</a></li>
                    <li><a href="#" data-seek="300" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Demo (5:00)') }}</a></li>
                    <li><a href="#" data-seek="1800" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Q&A (30:00)') }}</a></li>
                </ul>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="gv-card space-y-2">
                <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">
                    {{ get_phrase('Resources') }}
                </h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="#" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Slides') }}</a></li>
                    <li><a href="#" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Notes') }}</a></li>
                </ul>
            </div>
            <div class="gv-card space-y-2">
                <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">
                    {{ get_phrase('More from this series') }}
                </h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="#" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Episode 1') }}</a></li>
                    <li><a href="#" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Episode 2') }}</a></li>
                </ul>
            </div>
        </aside>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
