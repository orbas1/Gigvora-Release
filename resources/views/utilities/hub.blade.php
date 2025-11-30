@extends('layouts.app')

@section('title', get_phrase('Utilities Hub'))

@section('page-header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <p class="gv-eyebrow">{{ get_phrase('Utilities Addon') }}</p>
            <h1 class="text-3xl font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Professional Utilities & Network Hub') }}
            </h1>
            <p class="gv-muted mt-1 max-w-2xl">
                {{ get_phrase('Access connections, upgraded profiles, escrow tooling, stories/post enhancements, reactions, and hashtag discovery from one place.') }}
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('utilities.network') }}" class="gv-btn gv-btn-primary">
                {{ get_phrase('Open My Network') }}
            </a>
            <a href="{{ route('utilities.professional') }}" class="gv-btn gv-btn-ghost">
                {{ get_phrase('Upgrade Profile') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid gap-6 md:grid-cols-2">
        @foreach ($cards as $card)
            <section id="{{ $card['key'] }}" class="gv-card flex flex-col gap-4 {{ $card['enabled'] ? '' : 'opacity-75' }}">
                <div>
                    <h2 class="text-xl font-semibold text-[var(--gv-color-neutral-900)]">
                        {{ $card['title'] }}
                    </h2>
                    <p class="gv-muted mt-1">
                        {{ $card['description'] }}
                    </p>
                </div>

                @if (! $card['enabled'])
                    <div class="gv-mobile-chip text-[var(--gv-color-warning)] border-[var(--gv-color-warning)]">
                        {{ get_phrase('Enable this feature via config/pro_network_utilities_security_analytics.php') }}
                    </div>
                @endif

                <div class="flex flex-wrap gap-3">
                    @foreach ($card['actions'] as $action)
                        <a
                            href="{{ $action['route'] }}"
                            class="gv-btn {{ ($action['type'] ?? 'secondary') === 'primary' ? 'gv-btn-primary' : 'gv-btn-ghost' }}"
                            @if (! $card['enabled']) aria-disabled="true" @endif
                        >
                            {{ $action['label'] }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
@endsection

