@php
    $currentRoute = Route::currentRouteName();

    $navItems = [
        [
            'label' => get_phrase('Feed'),
            'route' => route('timeline'),
            'icon' => asset('storage/images/timeline-2.svg'),
            'active' => in_array($currentRoute, ['timeline', 'single.post']),
        ],
        [
            'label' => get_phrase('Memories'),
            'route' => route('memories'),
            'icon' => asset('storage/images/memories.svg'),
            'active' => $currentRoute === 'memories',
        ],
        [
            'label' => get_phrase('Blog'),
            'route' => route('blogs'),
            'icon' => asset('storage/images/blogging-2.svg'),
            'active' => in_array($currentRoute, ['blogs', 'create.blog', 'myblog', 'blog.edit', 'single.blog', 'category.blog']),
        ],
    ];

    if (config('jobs.features.enabled') && Route::has('jobs.index')) {
        $navItems[] = [
            'label' => get_phrase('Jobs'),
            'route' => route('jobs.index'),
            'icon' => asset('storage/images/jobs.svg'),
            'active' => \Illuminate\Support\Str::startsWith($currentRoute, 'jobs.') ||
                \Illuminate\Support\Str::startsWith($currentRoute, 'employer.'),
        ];
    }

    if (addon_status('fundraiser') == 1) {
        $navItems[] = [
            'label' => get_phrase('Fundraiser'),
            'route' => route('fundraiser.index'),
            'icon' => asset('assets/frontend/css/fundraiser/images/fundraiser/explore.svg'),
            'active' => $currentRoute === 'fundraiser.index',
        ];
    }

    if (addon_status('paid_content') == 1) {
        $navItems[] = [
            'label' => get_phrase('Paid content'),
            'route' => route('paid.content'),
            'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 512 512" fill="currentColor"><path d="M512 256c0 141.4-114.6 256-256 256S0 397.4 0 256 114.6 0 256 0s256 114.6 256 256Z" opacity=".25"/><path d="M270.91 131.2v32.26c19.64 1.67 37.77 5.83 54.4 12.46 10.67 3.98 19.98 8.68 27.94 14.08l-15.49 28.45c-1.19-1.18-3.35-2.79-6.44-4.79-3.12-2.01-6.94-3.99-11.42-5.94-4.49-1.95-9.36-3.65-14.57-5.1-5.27-1.33-10.49-2.01-15.63-2.02-13.73 0-23.06 2.32-27.99 6.97-4.96 4.63-7.46 10.62-7.49 18-.02 3.74.82 6.96 2.55 9.65 1.73 2.69 4.23 4.84 7.53 6.43 3.27 1.62 7.54 3.17 12.79 4.64 2.67.75 5.58 1.58 8.69 2.5 6.73 1.9 13.8 3.98 21.15 6.2 8.84 2.4 16.85 4.95 24.07 7.67 7.21 2.7 13.3 6.08 18.28 10.13 4.99 4.04 8.86 9.14 11.61 15.31 2.73 6.16 4.11 13.12 4.11 20.87 0 9.65-1.82 17.9-5.44 24.76-3.65 6.85-8.56 12.42-14.74 16.71-6.18 4.22-13.3 7.31-21.33 9.27-4.4 1.06-8.92 1.84-13.55 2.35v34.96h-30v-66.13c-7.34-.64-14.63-2.08-21.84-4.28-10.36-3.16-19.63-7.73-27.82-13.7l15.49-30.22c1.53 1.5 4.28 3.54 8.27 6.13 4.01 2.58 8.8 4.96 14.37 7.14 5.53 2.21 11.6 4.1 18.25 5.67 5.42 1.29 10.94 1.98 16.56 2.16 1.22.06 2.46.08 3.69.08 9.93 0 17.97-1.57 24.11-4.71 6.15-3.17 9.22-8.41 9.22-15.71 0-3.63-.99-6.82-2.99-9.56-1.94-2.76-5-5.13-9.17-7.12-4.13-2.02-9.04-3.93-14.74-5.76-1.69-.53-3.5-1.08-5.41-1.65-4.38-1.3-9.14-2.63-14.3-4.04-8.72-2.42-16.36-4.94-22.93-7.57-6.57-2.65-12.1-5.95-16.59-9.9-4.48-3.94-7.78-8.75-9.89-14.43-2.11-5.71-3.16-12.24-3.16-19.62 0-9.15 1.73-17.24 5.21-24.3 3.49-7.02 8.42-12.9 14.77-17.63 6.35-4.71 13.9-8.27 22.64-10.66 3.16-.87 6.43-1.55 9.81-2.07v-32.68z"/></svg>',
            'active' => in_array($currentRoute, ['paid.content', 'creator.timeline', 'creator', 'settings', 'general.timeline']),
        ];
    }

    $groups = \App\Models\Group_member::with('getGroup')
        ->where('user_id', auth()->user()->id)
        ->where('is_accepted', 1)
        ->latest('id')
        ->limit(6)
        ->get()
        ->map(function ($membership) {
            return $membership->getGroup;
        })
        ->filter();

    $ownedPages = \App\Models\Page::where('user_id', auth()->user()->id)
        ->latest('id')
        ->limit(6)
        ->get();

    $likedPages = \App\Models\Page_like::with('pageData')
        ->where('user_id', auth()->user()->id)
        ->latest('id')
        ->limit(6)
        ->get()
        ->map(function ($like) {
            return $like->pageData;
        })
        ->filter();

    $pages = $ownedPages->merge($likedPages)->unique('id')->take(6);
@endphp

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header res_logo d-lg-none py-4">
        <div class="logo">
            <img class="max-width-200" width="80%" src="{{ asset('storage/logo/dark/' . get_settings('system_dark_logo')) }}" alt="">
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">x</button>
    </div>
    <div class="offcanvas-body s_offcanvas">
        <div class="space-y-4">
            <div class="gv-card p-0">
                <nav class="flex flex-col gap-1 p-4">
                    @include('frontend.partials.nav-items', ['navItems' => $navItems])
                </nav>
                <div class="px-4 pb-4">
                    @if ($groups->isNotEmpty())
                        <div class="gv-community-collection">
                            <p class="gv-community-collection__title">{{ get_phrase('My groups') }}</p>
                            @foreach ($groups as $group)
                                <a href="{{ route('single.group', $group->id) }}" class="gv-community-item">
                                    <img src="{{ get_group_logo($group->logo, 'logo') }}" alt="{{ $group->title }}">
                                    <span>{{ ellipsis($group->title, 26) }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    @if ($pages->isNotEmpty())
                        <div class="gv-community-collection">
                            <p class="gv-community-collection__title">{{ get_phrase('My pages') }}</p>
                            @foreach ($pages as $page)
                                <a href="{{ route('single.page', $page->id) }}" class="gv-community-item">
                                    <img src="{{ get_page_logo($page->logo, 'logo') }}" alt="{{ $page->title }}">
                                    <span>{{ ellipsis($page->title, 26) }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="gv-card text-sm text-[var(--gv-color-neutral-500)] space-y-2 p-4">
                <div class="flex gap-4 text-xs uppercase tracking-wide text-[var(--gv-color-neutral-400)]">
                    <a href="{{ route('about.view') }}">{{ get_phrase('About') }}</a>
                    <a href="{{ route('policy.view') }}">{{ get_phrase('Privacy Policy') }}</a>
                </div>
                <p class="text-xs">© {{ date('Y') }} {{ get_settings('system_name') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="hidden lg:block space-y-4">
    <div class="gv-card p-0">
        <nav class="flex flex-col gap-1 p-4">
            @include('frontend.partials.nav-items', ['navItems' => $navItems])
        </nav>
        <div class="px-4 pb-4">
            @if ($groups->isNotEmpty())
                <div class="gv-community-collection">
                    <p class="gv-community-collection__title">{{ get_phrase('My groups') }}</p>
                    @foreach ($groups as $group)
                        <a href="{{ route('single.group', $group->id) }}" class="gv-community-item">
                            <img src="{{ get_group_logo($group->logo, 'logo') }}" alt="{{ $group->title }}">
                            <span>{{ ellipsis($group->title, 26) }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
            @if ($pages->isNotEmpty())
                <div class="gv-community-collection">
                    <p class="gv-community-collection__title">{{ get_phrase('My pages') }}</p>
                    @foreach ($pages as $page)
                        <a href="{{ route('single.page', $page->id) }}" class="gv-community-item">
                            <img src="{{ get_page_logo($page->logo, 'logo') }}" alt="{{ $page->title }}">
                            <span>{{ ellipsis($page->title, 26) }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="gv-card text-sm text-[var(--gv-color-neutral-500)] space-y-2 p-4">
        <div class="flex gap-4 text-xs uppercase tracking-wide text-[var(--gv-color-neutral-400)]">
            <a href="{{ route('about.view') }}">{{ get_phrase('About') }}</a>
            <a href="{{ route('policy.view') }}">{{ get_phrase('Privacy Policy') }}</a>
        </div>
        <p class="text-xs">© {{ date('Y') }} {{ get_settings('system_name') }}</p>
    </div>
</div>
