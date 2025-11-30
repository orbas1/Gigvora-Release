@php(
    $user = auth()->user();
    $roles = config('freelance.roles');
    $freelancerRole = $roles['seller'] ?? 'seller';
    $clientRole = $roles['buyer'] ?? 'buyer';
    $isFreelancer = $user && method_exists($user, 'hasRole') ? $user->hasRole($freelancerRole) : true;
    $isClient = $user && method_exists($user, 'hasRole') ? $user->hasRole($clientRole) : true;
)

<nav class="freelance-nav" aria-label="{{ __('Freelance navigation') }}">
    <div class="freelance-nav__header">
        <span class="freelance-nav__title">{{ __('Workspace links') }}</span>
        <p class="freelance-nav__summary">{{ __('Jump between gigs, projects, escrow, disputes and utilities from one spot.') }}</p>
    </div>

    <div class="freelance-nav__columns">
        @if($isFreelancer)
            <div class="freelance-nav__column" aria-label="{{ __('Freelancer links') }}">
                <h6 class="freelance-nav__column-title">{{ __('Freelancer') }}</h6>
                <ul class="freelance-nav__list">
                    <li><a class="gv-side-link" href="{{ route('freelance.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a class="gv-side-link" href="{{ route('freelance.gigs.orders') }}">{{ __('Gig orders') }}</a></li>
                    <li><a class="gv-side-link" href="{{ route('freelance.seller.gigs.list') }}">{{ __('My gigs') }}</a></li>
                    <li><a class="gv-side-link" href="{{ route('freelance.projects.index') }}">{{ __('Browse projects') }}</a></li>
                    <li><a class="gv-side-link" href="{{ route('freelance.gigs.activity', ['slug' => '']) }}" onclick="return false;">{{ __('Contracts & milestones') }}</a></li>
                </ul>
            </div>
        @endif

        @if($isClient)
            <div class="freelance-nav__column" aria-label="{{ __('Client links') }}">
                <h6 class="freelance-nav__column-title">{{ __('Client') }}</h6>
                <ul class="freelance-nav__list">
                    <li><a class="gv-side-link" href="{{ route('freelance.dashboard') }}">{{ __('Client dashboard') }}</a></li>
                    <li><a class="gv-side-link" href="{{ route('freelance.buyer.projects.create') }}">{{ __('Post project') }}</a></li>
                    <li><a class="gv-side-link" href="{{ route('freelance.disputes.index') }}">{{ __('Dispute centre') }}</a></li>
                    <li><a class="gv-side-link" href="{{ route('freelance.invoices.index') }}">{{ __('Invoices & escrow') }}</a></li>
                </ul>
            </div>
        @endif

        <div class="freelance-nav__column" aria-label="{{ __('Shared tools') }}">
            <h6 class="freelance-nav__column-title">{{ __('Shared') }}</h6>
            <ul class="freelance-nav__list">
                <li><a class="gv-side-link" href="{{ route('freelance.settings') }}">{{ __('Profile & billing') }}</a></li>
                <li><a class="gv-side-link" href="{{ route('freelance.favorites.index') }}">{{ __('Saved items') }}</a></li>
                <li><a class="gv-side-link" href="{{ route('freelance.packages.index') }}">{{ __('Packages & credits') }}</a></li>
            </ul>
        </div>
    </div>
</nav>
