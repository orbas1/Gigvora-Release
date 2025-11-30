@php
    $navigation = config('webinar_networking_interview_podcast.navigation', []);
    $features = config('webinar_networking_interview_podcast.features', []);

    $items = [
        [
            'label' => __('Webinars'),
            'route' => $navigation['webinar_route'] ?? 'wnip.webinars.index',
            'feature' => 'webinars',
            'icon' => 'video',
            'match' => 'wnip.webinars.*',
        ],
        [
            'label' => __('Networking'),
            'route' => $navigation['networking_route'] ?? 'wnip.networking.index',
            'feature' => 'networking',
            'icon' => 'users',
            'match' => 'wnip.networking.*',
        ],
        [
            'label' => __('Podcasts'),
            'route' => $navigation['podcast_route'] ?? 'wnip.podcasts.index',
            'feature' => 'podcasts',
            'icon' => 'microphone',
            'match' => 'wnip.podcasts.*',
        ],
        [
            'label' => __('Interviews'),
            'route' => $navigation['interview_route'] ?? 'wnip.interviews.index',
            'feature' => 'interviews',
            'icon' => 'calendar',
            'match' => 'wnip.interviews.*',
        ],
    ];

    $iconPaths = [
        'video' => 'M3 6a3 3 0 013-3h8a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm13 4l4-2v8l-4-2V10z',
        'users' => 'M10 12a4 4 0 10-4-4 4 4 0 004 4zm-6 8a6 6 0 0112 0zm13-8a3 3 0 11-3-3 3 3 0 013 3zm-2.75 4A6 6 0 0121 20v2h-4.5',
        'microphone' => 'M12 14a4 4 0 004-4V5a4 4 0 00-8 0v5a4 4 0 004 4zm6-4a6 6 0 01-12 0m6 6v3m-4 0h8',
        'calendar' => 'M7 2v3M17 2v3M4 7h16M5 11h4v4H5z',
    ];
@endphp

<nav class="live-shell__nav" aria-label="{{ __('Live & events') }}">
    <div class="live-nav__title">{{ $navigation['menu_label'] ?? __('Live & Events') }}</div>
    <ul class="live-nav__list">
        @foreach($items as $item)
            @continue(($features[$item['feature']] ?? false) === false)
            @continue(! Route::has($item['route']))
            <li>
                <a
                    data-live-nav-link
                    class="live-nav__link"
                    href="{{ route($item['route']) }}"
                    aria-current="{{ request()->routeIs($item['match']) ? 'page' : 'false' }}"
                >
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $iconPaths[$item['icon']] ?? $iconPaths['calendar'] }}" />
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>

