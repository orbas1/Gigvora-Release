@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Networking Session') }}</p>
        <h1 class="live-header__title">{{ $networkingSession->title }}</h1>
        <p class="live-header__subtitle">{{ __('Connect with peers in curated rotations and private chats.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_360px]">
    <div class="space-y-4">
        <div class="gv-card space-y-2">
            <div class="flex flex-col gap-1">
                <p class="gv-eyebrow mb-1">{{ get_phrase('Networking session') }}</p>
                <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)]">{{ $session->title }}</h1>
                <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">
                    {{ $session->starts_at?->format('M j • g:i A') }}
                    • {{ $session->rotation_interval }} {{ get_phrase('second rotations') }}
                    @if($session->rotation_count)
                        • {{ get_phrase(':count total rotations', ['count' => $session->rotation_count]) }}
                    @endif
                </p>
            </div>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ get_phrase('Hosted by :host', ['host' => optional($session->host)->name ?? get_phrase('Host')]) }}
            </p>
        </div>

        <div class="gv-card space-y-3">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Description') }}</h3>
            <p class="text-sm text-[var(--gv-color-neutral-700)] leading-relaxed">
                {!! nl2br(e($session->description)) !!}
            </p>
            <div>
                <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-2">
                    {{ get_phrase('What to expect') }}
                </h4>
                <ul class="list-disc pl-5 text-sm text-[var(--gv-color-neutral-600)] space-y-1">
                    <li>{{ get_phrase('Meet new peers every :seconds seconds', ['seconds' => $session->rotation_interval ?? 60]) }}</li>
                    <li>{{ get_phrase('Auto-rotation and partner assignments') }}</li>
                    <li>{{ get_phrase('Exportable connection list and reminders') }}</li>
                    <li>{{ get_phrase('Post-session follow-up plan with reminders and contact exchange') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <aside class="space-y-4">
        <div class="gv-card space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                        {{ trans_choice('{0}Be first to register|{1}1 participant|[2,*]:count participants', $session->participants->count(), ['count' => $session->participants->count()]) }}
                    </p>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                        {{ ucfirst($session->status ?? 'scheduled') }}
                    </p>
                </div>
                <span class="gv-pill">
                    {{ get_phrase('Rotation :seconds s', ['seconds' => $session->rotation_interval ?? 60]) }}
                </span>
            </div>

            @auth
                <form method="post" action="{{ route('wnip.networking.register', $session) }}" class="space-y-3">
                    @csrf
                    <div class="gv-alert gv-alert-info" role="status">
                        <div class="font-semibold">{{ get_phrase('Ticketing') }}</div>
                        <p class="text-sm mb-0">
                            {{ $session->is_paid ? get_phrase('Paid session from :amount', ['amount' => currency_format($session->price ?? 0)]) : get_phrase('Free ticket – instant confirmation') }}
                        </p>
                    </div>
                    <button class="gv-btn gv-btn-primary w-full" type="submit" @if($participant) aria-disabled="true" @endif>
                        {{ $participant ? get_phrase('Registered') : get_phrase('Register') }}
                    </button>
                </form>
                <a class="gv-btn gv-btn-ghost w-full" href="{{ route('wnip.networking.waiting', $session) }}">
                    {{ get_phrase('Join waiting room') }}
                </a>
            @else
                <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                    {{ get_phrase('Sign in to join this networking lounge.') }}
                </p>
            @endauth
        </div>

        <div class="gv-card space-y-2">
            <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Participants') }}
            </h4>
            <div class="space-y-2 max-h-64 overflow-auto">
                @forelse($session->participants as $row)
                    <div class="flex items-center justify-between text-sm">
                        <span>{{ get_phrase('User #:id', ['id' => $row->user_id]) }}</span>
                        <span class="text-xs text-[var(--gv-color-neutral-500)]">{{ ucfirst($row->status ?? 'invited') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">
                        {{ get_phrase('No participants yet.') }}
                    </p>
                @endforelse
            </div>
        </div>

        <div class="gv-card space-y-3">
            <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Follow-up & reminders') }}</h4>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ get_phrase('Add a reminder to send your notes, share your contact card, and confirm next steps after rotations end.') }}
            </p>
            <ul class="list-disc pl-5 text-sm text-[var(--gv-color-neutral-600)] space-y-1">
                <li>{{ get_phrase('Schedule a recap 15 minutes after the final rotation (Utilities reminder).') }}</li>
                <li>{{ get_phrase('Export matched contacts to CRM or Jobs/Freelance invites once the session is marked complete.') }}</li>
                <li>{{ get_phrase('Keep notes synced so profile/feed follow-ups surface the new connections.') }}</li>
            </ul>
            <div class="grid gap-2 sm:grid-cols-2">
                <button class="gv-btn gv-btn-secondary" type="button">{{ get_phrase('Add reminder') }}</button>
                <button class="gv-btn gv-btn-ghost" type="button">{{ get_phrase('Download roster (.csv)') }}</button>
            </div>
        </div>
    </aside>
</div>
@endsection
