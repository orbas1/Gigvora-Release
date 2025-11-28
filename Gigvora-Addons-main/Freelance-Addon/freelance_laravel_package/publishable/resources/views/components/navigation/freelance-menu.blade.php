@php(
    $user = auth()->user();
    $isFreelancer = $user && method_exists($user, 'hasRole') ? $user->hasRole('seller') : true;
    $isClient = $user && method_exists($user, 'hasRole') ? $user->hasRole('buyer') : true;
)

<nav class="freelance-nav" aria-label="Freelance navigation">
    <div class="freelance-nav__header">
        <span class="freelance-nav__title">{{ __('Freelance') }}</span>
        <p class="freelance-nav__summary">{{ __('Access Gigvora freelance dashboards, projects, and contracts without leaving the main experience.') }}</p>
    </div>

    <div class="freelance-nav__columns">
        @if($isFreelancer)
            <div class="freelance-nav__column" aria-label="Freelancer links">
                <h6 class="freelance-nav__column-title">{{ __('Freelancer') }}</h6>
                <ul class="freelance-nav__list">
                    <li class="freelance-nav__item">
                        <a class="freelance-nav__link" href="{{ route('dashboard') }}">{{ __('Freelance Dashboard') }}</a>
                    </li>
                    <li class="freelance-nav__item">
                        <a class="freelance-nav__link" href="{{ route('gig-list') }}">{{ __('My Gigs') }}</a>
                    </li>
                    <li class="freelance-nav__item">
                        <a class="freelance-nav__link" href="{{ route('project-listing') }}">{{ __('Browse Projects') }}</a>
                    </li>
                    <li class="freelance-nav__item">
                        <a class="freelance-nav__link" href="{{ route('gig-orders') }}">{{ __('Contracts') }}</a>
                    </li>
                </ul>
            </div>
        @endif

        @if($isClient)
            <div class="freelance-nav__column" aria-label="Client links">
                <h6 class="freelance-nav__column-title">{{ __('Client') }}</h6>
                <ul class="freelance-nav__list">
                    <li class="freelance-nav__item">
                        <a class="freelance-nav__link" href="{{ route('dashboard') }}">{{ __('Client Dashboard') }}</a>
                    </li>
                    <li class="freelance-nav__item">
                        <a class="freelance-nav__link" href="{{ route('project-listing') }}">{{ __('My Projects') }}</a>
                    </li>
                    <li class="freelance-nav__item">
                        <a class="freelance-nav__link" href="{{ route('invoices') }}">{{ __('Contracts & Escrow') }}</a>
                    </li>
                </ul>
            </div>
        @endif

        <div class="freelance-nav__column" aria-label="Shared links">
            <h6 class="freelance-nav__column-title">{{ __('Shared') }}</h6>
            <ul class="freelance-nav__list">
                <li class="freelance-nav__item">
                    <a class="freelance-nav__link" href="{{ route('settings') }}">{{ __('Profile & Settings') }}</a>
                </li>
                <li class="freelance-nav__item">
                    <a class="freelance-nav__link" href="{{ route('favourite-items') }}">{{ __('Saved Items') }}</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('styles')
<style>
    .freelance-nav {
        background: var(--gigvora-surface, #ffffff);
        border: 1px solid var(--gigvora-border, #e5e7eb);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
    }

    .freelance-nav__header {
        margin-bottom: 12px;
    }

    .freelance-nav__title {
        display: inline-block;
        font-weight: 700;
        color: var(--gigvora-heading, #0f172a);
    }

    .freelance-nav__summary {
        margin: 6px 0 0;
        color: var(--gigvora-muted, #475569);
        font-size: 0.95rem;
    }

    .freelance-nav__columns {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }

    .freelance-nav__column-title {
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--gigvora-heading, #0f172a);
    }

    .freelance-nav__list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .freelance-nav__item + .freelance-nav__item {
        margin-top: 6px;
    }

    .freelance-nav__link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--gigvora-link, #0ea5e9);
        text-decoration: none;
        font-weight: 600;
    }

    .freelance-nav__link:hover,
    .freelance-nav__link:focus {
        color: var(--gigvora-link-strong, #0284c7);
        text-decoration: underline;
        outline: none;
    }

    @media (max-width: 767px) {
        .freelance-nav {
            padding: 16px;
        }

        .freelance-nav__columns {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
