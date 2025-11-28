@php(
    $user = auth()->user();
    $roles = config('freelance.default_roles');
    $freelancerRole = $roles['freelancer'] ?? 'freelancer';
    $clientRole = $roles['client'] ?? 'client';
    $isFreelancer = $user && method_exists($user, 'hasRole') ? $user->hasRole($freelancerRole) : false;
    $isClient = $user && method_exists($user, 'hasRole') ? $user->hasRole($clientRole) : false;
)

@once
    @push('styles')
        @vite('resources/css/freelance/navigation.css')
    @endpush
@endonce

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

        @if($isFreelancer || $isClient)
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
        @else
            <div class="freelance-nav__column" aria-label="Freelance access">
                <p class="freelance-nav__empty">{{ __('Sign in with your Gigvora account to see freelance dashboards tied to your role.') }}</p>
            </div>
        @endif
    </div>
</nav>
