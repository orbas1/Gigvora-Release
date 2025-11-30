@php
    $user = auth()->user();
    $persona = app(\App\Support\Persona\PersonaResolver::class)->resolve($user);
    $resolveRoute = function (?string $name, string $fallback = '#') {
        return $name && \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
    };

    $memberIcons = [
        ['key' => 'feed', 'label' => get_phrase('Feed'), 'icon' => 'fa-solid fa-house', 'route' => $resolveRoute('timeline'), 'source' => 'feed'],
        ['key' => 'projects', 'label' => get_phrase('Projects'), 'icon' => 'fa-solid fa-diagram-project', 'route' => $resolveRoute('freelance.dashboard', $resolveRoute('jobs')), 'source' => 'freelance_project'],
        ['key' => 'gigs', 'label' => get_phrase('Find a gig'), 'icon' => 'fa-solid fa-people-carry-box', 'route' => $resolveRoute('freelance.projects.index', $resolveRoute('jobs')), 'source' => 'freelance_gig'],
        ['key' => 'applications', 'label' => get_phrase('Applications'), 'icon' => 'fa-solid fa-file-lines', 'route' => $resolveRoute('job.my.apply.list', $resolveRoute('jobs')), 'source' => 'job_application'],
        ['key' => 'interviews', 'label' => get_phrase('Interviews'), 'icon' => 'fa-solid fa-video', 'route' => $resolveRoute('wnip.interviews.index', $resolveRoute('liveCenter.hub')), 'source' => 'jobs_interview'],
        ['key' => 'calendar', 'label' => get_phrase('Calendar'), 'icon' => 'fa-solid fa-calendar-days', 'route' => $resolveRoute('utilities.calendar.index'), 'source' => 'calendar'],
        ['key' => 'events', 'label' => get_phrase('Events'), 'icon' => 'fa-solid fa-ticket', 'route' => $resolveRoute('event'), 'source' => 'live_event'],
        ['key' => 'sessions', 'label' => get_phrase('Session bookings'), 'icon' => 'fa-solid fa-handshake-angle', 'route' => $resolveRoute('utilities.network'), 'source' => 'session_booking'],
    ];

    $professionalIcons = [
        ['key' => 'projects-search', 'label' => get_phrase('Projects search'), 'icon' => 'fa-solid fa-magnifying-glass', 'route' => $resolveRoute('freelance.projects.index', $resolveRoute('freelance.dashboard')), 'source' => 'projects_search'],
        ['key' => 'gig-orders', 'label' => get_phrase('Gig orders'), 'icon' => 'fa-solid fa-layer-group', 'route' => $resolveRoute('freelance.gigs.orders', $resolveRoute('freelance.dashboard')), 'source' => 'freelance_order'],
        ['key' => 'job-listings', 'label' => get_phrase('Job listings'), 'icon' => 'fa-solid fa-clipboard-list', 'route' => $resolveRoute('jobs'), 'source' => 'job_listing'],
        ['key' => 'events-pro', 'label' => get_phrase('Events'), 'icon' => 'fa-solid fa-ticket', 'route' => $resolveRoute('event'), 'source' => 'live_event'],
        ['key' => 'calendar-pro', 'label' => get_phrase('Calendar'), 'icon' => 'fa-solid fa-calendar-days', 'route' => $resolveRoute('utilities.calendar.index'), 'source' => 'calendar'],
        ['key' => 'sessions-pro', 'label' => get_phrase('Session bookings'), 'icon' => 'fa-solid fa-handshake-angle', 'route' => $resolveRoute('utilities.network'), 'source' => 'session_booking'],
    ];

    $commonIcons = [
        ['key' => 'marketplace', 'label' => get_phrase('Marketplace'), 'icon' => 'fa-solid fa-store', 'route' => $resolveRoute('allproducts'), 'source' => 'marketplace'],
        ['key' => 'videos', 'label' => get_phrase('Videos'), 'icon' => 'fa-solid fa-film', 'route' => $resolveRoute('videos'), 'source' => 'video_session'],
        ['key' => 'shorts', 'label' => get_phrase('Shorts'), 'icon' => 'fa-solid fa-clapperboard', 'route' => $resolveRoute('shorts', $resolveRoute('videos')), 'source' => 'shorts'],
    ];

    if ($persona === 'professional') {
        $primaryIcons = $professionalIcons;
    } elseif ($persona === 'hybrid') {
        $primaryIcons = array_values(collect($memberIcons)->merge($professionalIcons)->unique('key')->all());
    } else {
        $primaryIcons = $memberIcons;
    }

    $headerIcons = array_values(array_merge($primaryIcons, $commonIcons));

    $alertEvents = UtilitiesCalendarEvent::query()
        ->where('user_id', $user->id)
        ->where('status', '!=', 'acknowledged')
        ->whereBetween('starts_at', [now()->subDay(), now()->addDays(7)])
        ->get();

    $cadenceFor = function (?Carbon $date) {
        if (! $date) {
            return null;
        }
        $minutes = now()->diffInMinutes($date, false);
        if ($minutes <= 60) {
            return '1h';
        }
        if ($minutes <= 360) {
            return '6h';
        }
        if ($minutes <= 1440) {
            return '24h';
        }
        if ($minutes <= 4320) {
            return '3d';
        }
        return '7d';
    };

    $alerts = [];
    foreach ($alertEvents as $event) {
        $source = $event->source;
        if (! isset($alerts[$source])) {
            $alerts[$source] = [
                'count' => 0,
                'next' => $event->starts_at,
            ];
        }
        $alerts[$source]['count']++;
        if ($event->starts_at < $alerts[$source]['next']) {
            $alerts[$source]['next'] = $event->starts_at;
        }
    }

    foreach ($alerts as $source => $alert) {
        $alerts[$source]['cadence'] = $cadenceFor($alert['next']);
    }

    $headerBadge = Badge::where('user_id', $user->id)->first();
@endphp

<div class="custom-progress-bar">
    <div class="custom-progress"></div>
</div>
<header class="header header-default py-3 gv-shell-header">
    <nav class="navigation">
        <div class="container gv-shell-header-inner">
            <div class="row align-items-center">
                <div class="col-lg-2 col-sm-4">
                    <div class="logo-branding">
                        <button class="d-lg-none" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                class="fw-bold fa-solid fa-sliders-h"></i></button>
                        @php
                            $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description');
                        @endphp
                        <a class="navbar-brand mt-2" href="{{ route('timeline') }}"><img
                                src="{{ get_system_logo_favicon($system_light_logo, 'light') }}"
                                class="logo_height_width" alt="logo" /></a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-flex flex-column gap-3">
                    <div class="header-search gv-header-search">
                        <form action="{{ route('search') }}" method="GET" id="form_id" class="gv-header-search__form">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.3183 13.9465C13.1728 13.8595 13.033 13.7609 12.9049 13.6506C12.0022 12.763 11.1054 11.8696 10.2143 10.9762C10.1794 10.9414 10.1503 10.9008 10.1212 10.8602C7.50636 13.0357 3.622 12.6876 1.43814 10.0828C-0.739895 7.48374 -0.396301 3.60841 2.21851 1.43288C4.82749 -0.742649 8.71768 -0.394565 10.9015 2.21027C12.8117 4.49022 12.8117 7.80282 10.9015 10.0828C10.9481 10.1118 10.9889 10.1466 11.0297 10.1814C11.9207 11.069 12.8117 11.9624 13.7027 12.85C13.8134 12.9777 13.9124 13.1169 13.9997 13.2619V13.5056C13.924 13.7087 13.7668 13.8653 13.5629 13.9407H13.3183V13.9465ZM1.14114 6.12041C1.12949 8.88188 3.37159 11.1328 6.14363 11.1444C8.91568 11.156 11.1752 8.92249 11.1869 6.16102C11.1985 3.39955 8.96227 1.14861 6.1844 1.13701C3.41235 1.1254 1.15278 3.35894 1.13531 6.12041" fill="currentColor"/>
                            </svg>
                            <input type="search" class="rounded white-placeholder hashtag-link" name="search"
                                value="@isset($_GET['search']){{ $_GET['search'] }}@endisset"
                                placeholder="{{ get_phrase('Search Gigvora') }}">
                        </form>
                    </div>
                    <div class="gv-header-icons">
                        @foreach ($headerIcons as $icon)
                            @php
                                $alert = $alerts[$icon['source'] ?? ''] ?? null;
                            @endphp
                            <a href="{{ $icon['route'] }}"
                                class="gv-header-icon"
                                data-alert-source="{{ $alert ? $icon['source'] : '' }}"
                                title="{{ $icon['label'] }}">
                                <span class="gv-header-icon__glyph">
                                    <i class="{{ $icon['icon'] }}"></i>
                                </span>
                                <span class="gv-header-icon__label">{{ $icon['label'] }}</span>
                                @if ($alert)
                                    <span class="gv-header-icon__badge" data-source="{{ $icon['source'] }}">
                                        {{ $alert['count'] }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4 col-sm-8">
                    <div class="header-controls">
                        <div class="align-items-center d-flex justify-content-end g-12">
                            <div class="group-control">
                                <a href="{{ route('ai_image.image_generator') }}" class="notification-button" title="AI image generator"><i class="fa-solid fa-robot"></i></a>
                            </div>
                            <div class="group-control">
                                <a href="{{ route('profile.friends') }}" class="notification-button"><i
                                        class="fa-solid fa-user-group"></i></a>
                            </div>
                            @php
                                $last_msg = \App\Models\Chat::where('sender_id', $user->id)
                                    ->orWhere('reciver_id', $user->id)
                                    ->orderBy('id', 'DESC')
                                    ->limit('1')
                                    ->first();
                                if (!empty($last_msg)) {
                                    if ($last_msg->sender_id == $user->id) {
                                        $msg_to = $last_msg->reciver_id;
                                    } else {
                                        $msg_to = $last_msg->sender_id;
                                    }
                                }
                                $unread_msg = \App\Models\Chat::where('reciver_id', $user->id)
                                    ->where('read_status', '0')
                                    ->count();
                            @endphp
                            <div class="inbox-control">
                                <a href="@if(isset($msg_to)) {{ route('chat', $msg_to) }} @else {{route('chat','all')}} @endif"
                                    class="message_custom_button position-relative">
                                    <i class="fa-brands fa-rocketchat"></i>
                                    @if ($unread_msg > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notificatio_counter_bg">
                                            {{ get_phrase($unread_msg) }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            @php
                                $unread_notification = \App\Models\Notification::where('reciver_user_id', $user->id)
                                    ->where('status', '0')
                                    ->count();
                            @endphp

                            <div class="notify-control ">
                                <a class="notification-button position-relative" id="notification-button" href="javascript:;">
                                    <i class="fa-solid fa-bell"></i>
                                    @if ($unread_notification > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill notificatio_counter_bg">
                                            {{ get_phrase($unread_notification) }}
                                        </span>
                                    @endif
                                </a>
                                <div class="notification_panel" id="notification_panel">
                                    @include('frontend.notification.notification')
                                </div>
                            </div>
                            <div class="profile-control">
                                <a href="{{ route('profile') }}" class="profile-control__avatar" id="profileMenuTrigger">
                                    <img src="{{ get_user_image($user->photo, 'optimized') }}"
                                        class="rounded-circle" alt="{{ $user->name }}">
                                </a>
                                <button class="profile-control__caret" type="button" id="profileMenuButton" aria-label="{{ get_phrase('Open account menu') }}">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <div class="gv-profile-dropdown" id="profileMenuDropdown">
                                    <div class="gv-profile-dropdown__header">
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $user->name }}</p>
                                            <p class="text-xs text-muted mb-0">{{ $user->email }}</p>
                                        </div>
                                        @if ($headerBadge && $headerBadge->isActive())
                                            <span class="status-pill status-pill--success">{{ get_phrase('Verified') }}</span>
                                        @elseif ($headerBadge && $headerBadge->review_status === \App\Models\Badge::REVIEW_UNDER_REVIEW)
                                            <span class="status-pill status-pill--warning">{{ get_phrase('Under review') }}</span>
                                        @else
                                            <span class="status-pill">{{ get_phrase('Not verified') }}</span>
                                        @endif
                                    </div>
                                    <a class="gv-dropdown-link" href="{{ route('profile') }}">{{ get_phrase('View profile') }}</a>
                                    <a class="gv-dropdown-link" href="{{ route('badge') }}">{{ get_phrase('Gigvora Verify') }}</a>
                                    <button type="button" class="gv-dropdown-link" id="themeToggleControl">{{ get_phrase('Toggle theme') }}</button>
                                    @if ($user->user_role == 'admin')
                                        <a class="gv-dropdown-link"
                                            href="{{ route('admin.dashboard') }}">{{ get_phrase('Go to admin panel') }}</a>
                                    @endif
                                    <a class="gv-dropdown-link" href="{{ route('user.settings') }}">{{ get_phrase('Payment Settings') }}</a>
                                    <a class="gv-dropdown-link" href="{{ route('user.password.change') }}">{{ get_phrase('Change Password') }}</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="gv-dropdown-link" type="submit">{{ get_phrase('Log Out') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-lg-none mt-3">
                <div class="col-12">
                    <div class="header-search gv-header-search">
                        <form action="{{ route('search') }}" method="GET" class="gv-header-search__form">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.3183 13.9465C13.1728 13.8595 13.033 13.7609 12.9049 13.6506C12.0022 12.763 11.1054 11.8696 10.2143 10.9762C10.1794 10.9414 10.1503 10.9008 10.1212 10.8602C7.50636 13.0357 3.622 12.6876 1.43814 10.0828C-0.739895 7.48374 -0.396301 3.60841 2.21851 1.43288C4.82749 -0.742649 8.71768 -0.394565 10.9015 2.21027C12.8117 4.49022 12.8117 7.80282 10.9015 10.0828C10.9481 10.1118 10.9889 10.1466 11.0297 10.1814C11.9207 11.069 12.8117 11.9624 13.7027 12.85C13.8134 12.9777 13.9124 13.1169 13.9997 13.2619V13.5056C13.924 13.7087 13.7668 13.8653 13.5629 13.9407H13.3183V13.9465ZM1.14114 6.12041C1.12949 8.88188 3.37159 11.1328 6.14363 11.1444C8.91568 11.156 11.1752 8.92249 11.1869 6.16102C11.1985 3.39955 8.96227 1.14861 6.1844 1.13701C3.41235 1.1254 1.15278 3.35894 1.13531 6.12041" fill="currentColor"/>
                            </svg>
                            <input type="search" class="rounded white-placeholder hashtag-link" name="search"
                                value="@isset($_GET['search']){{ $_GET['search'] }}@endisset"
                                placeholder="{{ get_phrase('Search Gigvora') }}">
                        </form>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="gv-header-icons gv-header-icons--mobile">
                        @foreach ($headerIcons as $icon)
                            @php $alert = $alerts[$icon['source'] ?? ''] ?? null; @endphp
                            <a href="{{ $icon['route'] }}" class="gv-header-icon"
                                data-alert-source="{{ $alert ? $icon['source'] : '' }}">
                                <span class="gv-header-icon__glyph"><i class="{{ $icon['icon'] }}"></i></span>
                                <span class="gv-header-icon__label">{{ $icon['label'] }}</span>
                                @if ($alert)
                                    <span class="gv-header-icon__badge" data-source="{{ $icon['source'] }}">{{ $alert['count'] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
(function(){
    const profileMenuTrigger = document.getElementById('profileMenuTrigger');
    const profileMenuButton = document.getElementById('profileMenuButton');
    const profileMenuDropdown = document.getElementById('profileMenuDropdown');
    let hideTimeout = null;

    const openMenu = () => {
        clearTimeout(hideTimeout);
        profileMenuDropdown.classList.add('show');
    };

    const closeMenu = () => {
        hideTimeout = setTimeout(() => profileMenuDropdown.classList.remove('show'), 150);
    };

    if (profileMenuTrigger && profileMenuDropdown) {
        profileMenuTrigger.addEventListener('mouseenter', openMenu);
        profileMenuTrigger.addEventListener('mouseleave', closeMenu);
        profileMenuDropdown.addEventListener('mouseenter', () => clearTimeout(hideTimeout));
        profileMenuDropdown.addEventListener('mouseleave', closeMenu);
    }

    if (profileMenuButton && profileMenuDropdown) {
        profileMenuButton.addEventListener('click', (event) => {
            event.preventDefault();
            profileMenuDropdown.classList.toggle('show');
        });
    }

    const themeToggle = document.getElementById('themeToggleControl');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('theme-alt');
        });
    }

    const csrf = document.querySelector('meta[name="csrf_token"]').getAttribute('content');
    document.querySelectorAll('[data-alert-source]').forEach((icon) => {
        const source = icon.dataset.alertSource;
        if (!source) {
            return;
        }

        const acknowledge = () => {
            const badge = icon.querySelector('[data-source="' + source + '"]');
            if (badge) {
                badge.remove();
            }
            icon.removeAttribute('data-alert-source');
        };

        icon.addEventListener('mouseenter', () => {
            fetch('{{ route('utilities.header-alerts.ack') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ source }),
            }).finally(acknowledge);
        }, { once: true });
    });
})();
</script>
