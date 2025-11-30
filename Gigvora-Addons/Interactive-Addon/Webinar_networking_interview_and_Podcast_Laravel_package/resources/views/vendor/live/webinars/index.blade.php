@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Live & Events') }}</p>
        <h1 class="live-header__title">{{ __('Webinars') }}</h1>
        <p class="live-header__subtitle">{{ __('Discover live, upcoming, and recorded webinars tailored to your interests.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                {{ get_phrase('Webinars catalogue') }}
            </h1>
            <p class="gv-muted mb-0">
                {{ get_phrase('Discover live, upcoming, and recorded webinars with Gigvora experiences.') }}
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a class="gv-btn gv-btn-primary" href="{{ route('wnip.webinars.index', ['upcoming' => 1]) }}">
                {{ get_phrase('Host a webinar') }}
            </a>
            <a class="gv-btn gv-btn-ghost" href="{{ route('wnip.webinars.recordings') }}">
                {{ get_phrase('Replay library') }}
            </a>
        </div>
    </div>

    <form method="get" class="gv-card space-y-4">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <label class="space-y-1">
                <span class="gv-label">{{ get_phrase('Search') }}</span>
                <input type="text" name="q" class="gv-input" value="{{ $filters['q'] ?? '' }}"
                    placeholder="{{ get_phrase('Title or description') }}">
            </label>
            <div class="space-y-1">
                <span class="gv-label">{{ get_phrase('Only upcoming') }}</span>
                <label class="inline-flex items-center gap-2 text-sm text-[var(--gv-color-neutral-600)]">
                    <input type="checkbox"
                        class="h-4 w-4 rounded border-[var(--gv-color-border)] text-[var(--gv-color-primary-600)]"
                        name="upcoming" value="1" @checked($filters['upcoming'] ?? false)>
                    <span>{{ get_phrase('Show only upcoming sessions') }}</span>
                </label>
            </div>
            <div class="space-y-1">
                <span class="gv-label">{{ get_phrase('Include past sessions') }}</span>
                <label class="inline-flex items-center gap-2 text-sm text-[var(--gv-color-neutral-600)]">
                    <input type="checkbox"
                        class="h-4 w-4 rounded border-[var(--gv-color-border)] text-[var(--gv-color-primary-600)]"
                        name="past" value="1" @checked($filters['past'] ?? false)>
                    <span>{{ get_phrase('Include finished webinars') }}</span>
                </label>
            </div>
            <label class="space-y-1">
                <span class="gv-label">{{ get_phrase('Pricing') }}</span>
                <select class="gv-input" name="paid">
                    <option value="">{{ get_phrase('Any') }}</option>
                    <option value="0" @selected(($filters['paid'] ?? '') === '0')>{{ get_phrase('Free') }}</option>
                    <option value="1" @selected(($filters['paid'] ?? '') === '1')>{{ get_phrase('Paid') }}</option>
                </select>
            </label>
        </div>
        <div class="flex justify-end">
            <button class="gv-btn gv-btn-primary" type="submit">{{ get_phrase('Apply filters') }}</button>
        </div>
    </form>

    <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
        @forelse($webinars as $webinar)
            @include('wnip::components.event_card', [
                'title' => $webinar->title,
                'description' => Str::limit($webinar->description, 140),
                'meta' => optional($webinar->starts_at)->format('M j • g:i A'),
                'caption' => optional($webinar->host)->name ?? get_phrase('Host'),
                'status' => $webinar->is_live ? get_phrase('Live now') : ucfirst($webinar->status ?? 'Scheduled'),
                'tag' => $webinar->is_paid ? get_phrase('Paid') : get_phrase('Free'),
                'detail' => trans_choice('{0}No registrants yet|{1}1 registrant|[2,*]:count registrants', $webinar->registrations_count ?? 0, ['count' => $webinar->registrations_count ?? 0]),
                'href' => route('wnip.webinars.show', $webinar),
                'cta' => get_phrase('View details'),
            ])
        @empty
            <div class="lg:col-span-2 xl:col-span-3">
                <div class="gv-empty">
                    {{ get_phrase('No webinars found for the current filters.') }}
                </div>
            </div>
        @endforelse
    </div>

    <div>
        {{ $webinars->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-live-at]').forEach(function(el) {
        const startsAt = new Date(el.dataset.liveAt);
        const badge = el.querySelector('.live-badge');
        if (!badge) return;
        const tick = () => {
            const now = new Date();
            if (now >= startsAt) {
                badge.textContent = 'Live now';
                badge.classList.remove('bg-secondary');
                badge.classList.add('bg-danger');
            }
        };
        tick();
        setInterval(tick, 15000);
    });
</script>
@endpush
