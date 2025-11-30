@php
    $navigation = app(\App\Support\Navigation\NavigationBuilder::class)->build(auth()->user());
    $searchRoute = \Illuminate\Support\Facades\Route::has('search') ? route('search') : url('/search');

    $icons = [
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9.75 12 3l9 6.75v8.25a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 18V9.75zM9 21v-6a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 15 15v6" />',
        'briefcase' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 6H6A2.25 2.25 0 0 0 3.75 8.25v8.25a2.25 2.25 0 0 0 2.25 2.25h12a2.25 2.25 0 0 0 2.25-2.25V8.25A2.25 2.25 0 0 0 18 6h-2.25M8.25 6a2.25 2.25 0 0 1 2.25-2.25h3A2.25 2.25 0 0 1 15.75 6m-7.5 0h7.5M6 10.5h12" />',
        'handshake' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6.75 12 10.5l-2.25-2.25L3 15l2.25 2.25L9 13.5l3 3 6-6M16.5 4.5l3 3" />',
        'broadcast' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 6.75a6.75 6.75 0 1 1 7.5 11.07M12 9.75v4.5m0-4.5a2.25 2.25 0 1 1 0 4.5M4.5 12a7.5 7.5 0 0 0 7.5 7.5v0M4.5 12A7.5 7.5 0 0 1 12 4.5" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.25 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.75 8.25a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0zM4.5 19.5v-1.5a3 3 0 0 1 3-3h2.25M16.5 15h1.5a3 3 0 0 1 3 3v1.5" />',
        'layers' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 9 9-4.5L21 9l-9 4.5L3 9Z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m3 14.25 9 4.5 9-4.5" />',
        'chat' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 5.25h16.5v9a2.25 2.25 0 0 1-2.25 2.25H9.75L6 18.75v-2.25H6a2.25 2.25 0 0 1-2.25-2.25v-9Z" />',
        'megaphone' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15.75 8.25 4.5-2.25v7.5l-4.5-2.25m0-3v6m0-6a6.75 6.75 0 0 1-6.75 6.75H6.75m0 0V9.75m0 7.5-3 4.5" />',
        'sparkles' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v4.5M12 21v-4.5m7.5-4.5h-4.5m0 0 1.5 1.5-1.5 1.5m-9-3H3m0 0 1.5 1.5L3 13.5M7.5 6 9 9l1.5-3m5.25 9L15 18l-1.5-3" />',
        'bell' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.25 18.75a2.25 2.25 0 0 1-4.5 0m8.25-1.5H6a1.5 1.5 0 0 1-1.5-1.5 6 6 0 0 1 4.5-5.85V9a3 3 0 1 1 6 0v.9a6 6 0 0 1 4.5 5.85 1.5 1.5 0 0 1-1.5 1.5Z" />',
        'bookmark' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.75 4.5h10.5v16.5L12 15.75 6.75 21V4.5Z" />',
        'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.75 3v3m10.5-3v3M4.5 8.25h15M6 10.5h12a1.5 1.5 0 0 1 1.5 1.5v7.5A1.5 1.5 0 0 1 18 21H6a1.5 1.5 0 0 1-1.5-1.5v-7.5A1.5 1.5 0 0 1 6 10.5Z" />',
        'search' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.8-4.8m1.55-4.95a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z" />',
        'user-circle' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm3-6a3 3 0 1 0-6 0m9 3.75a6 6 0 0 0-12 0" />',
        'settings' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.5 6.75h3m-3 10.5h3m6.75-5.25a6.75 6.75 0 0 1-6.75 6.75h-3A6.75 6.75 0 0 1 3.75 12a6.75 6.75 0 0 1 6.75-6.75h3A6.75 6.75 0 0 1 21.75 12Z" />',
        'shield' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21.75c5.25-2.25 7.5-4.5 7.5-10.5V6.75l-7.5-3-7.5 3v4.5c0 6 2.25 8.25 7.5 10.5Z" />',
    ];

    $iconSvg = function (?string $key) use ($icons): string {
        if (! $key || ! isset($icons[$key])) {
            return '';
        }

        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">'.$icons[$key].'</svg>';
    };

    $isActive = function (array $item): bool {
        return request()->routeIs($item['match'] ?? $item['route']);
    };
@endphp

<nav x-data="{ open: false }" class="bg-[var(--gv-color-surface)] border-b border-[var(--gv-color-border)] shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                </a>

                <div class="hidden xl:flex items-center gap-1">
                    @foreach($navigation['primary'] as $item)
                        @php $active = $isActive($item); @endphp
                        <a href="{{ route($item['route']) }}"
                            class="gv-nav-icon {{ $active ? 'gv-nav-icon--active' : '' }}"
                            aria-current="{{ $active ? 'page' : 'false' }}"
                            title="{{ __($item['label']) }}">
                            <span class="gv-nav-icon__glyph">{!! $iconSvg($item['icon'] ?? null) !!}</span>
                            <span class="gv-nav-icon__label">{{ __($item['label']) }}</span>
                        </a>
                    @endforeach
                </div>

                <div class="hidden lg:flex items-center gap-2">
                    @foreach($navigation['groups'] as $group)
                        <x-dropdown align="left" width="64">
                            <x-slot name="trigger">
                                <button class="gv-icon-button" type="button" title="{{ __($group['label']) }}">
                                    {!! $iconSvg($group['icon'] ?? null) !!}
                                    <span class="sr-only">{{ __($group['label']) }}</span>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wide text-[var(--gv-color-neutral-500)]">
                                    {{ __($group['label']) }}
                                </div>
                                <div class="px-2 pb-2 space-y-1">
                                    @foreach($group['children'] as $child)
                                        <x-dropdown-link :href="route($child['route'])" :active="request()->routeIs($child['match'] ?? $child['route'])">
                                            {{ __($child['label']) }}
                                        </x-dropdown-link>
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-dropdown>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-2">
                <a href="{{ $searchRoute }}" class="gv-icon-button" title="{{ __('Search Gigvora') }}">
                    {!! $iconSvg('search') !!}
                    <span class="sr-only">{{ __('Search') }}</span>
                </a>

                @foreach($navigation['secondary'] as $secondary)
                    @php $active = $isActive($secondary); @endphp
                    <a href="{{ route($secondary['route']) }}"
                        class="gv-icon-button {{ $active ? 'gv-icon-button--active' : '' }}"
                        title="{{ __($secondary['label']) }}">
                        {!! $iconSvg($secondary['icon'] ?? null) !!}
                        <span class="sr-only">{{ __($secondary['label']) }}</span>
                    </a>
                @endforeach

                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-[var(--gv-color-border)] text-sm font-medium text-[var(--gv-color-neutral-700)] hover:border-[var(--gv-color-primary-300)] focus-visible:gv-focus-ring transition">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-2">
                            <div class="font-semibold text-[var(--gv-color-neutral-900)]">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-[var(--gv-color-neutral-500)]">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="border-t border-[var(--gv-color-border)] my-1"></div>
                        @foreach($navigation['settings'] as $settingsLink)
                            <x-dropdown-link :href="route($settingsLink['route'])" :active="request()->routeIs($settingsLink['match'] ?? $settingsLink['route'])">
                                {{ __($settingsLink['label']) }}
                            </x-dropdown-link>
                        @endforeach
                        @foreach($navigation['admin'] as $adminLink)
                            <x-dropdown-link :href="route($adminLink['route'])" :active="request()->routeIs($adminLink['match'] ?? $adminLink['route'])">
                                {{ __($adminLink['label']) }}
                            </x-dropdown-link>
                        @endforeach
                        <div class="border-t border-[var(--gv-color-border)] my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ get_phrase('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center xl:hidden">
                <button @click="open = ! open" type="button" class="gv-icon-button" aria-label="{{ __('Toggle navigation') }}">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M6 18 18 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="xl:hidden" x-show="open" x-transition>
        <div class="gv-mobile-nav-section">
            <div class="gv-mobile-nav-title">{{ __('Navigate') }}</div>
            <div class="grid grid-cols-3 gap-3">
                @foreach($navigation['primary'] as $item)
                    @php $active = $isActive($item); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="gv-nav-icon {{ $active ? 'gv-nav-icon--active' : '' }}"
                        aria-current="{{ $active ? 'page' : 'false' }}">
                        <span class="gv-nav-icon__glyph">{!! $iconSvg($item['icon'] ?? null) !!}</span>
                        <span class="gv-nav-icon__label">{{ __($item['label']) }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="gv-mobile-nav-section">
            <div class="gv-mobile-nav-title">{{ __('Explore Addons') }}</div>
            <div class="space-y-4">
                @foreach($navigation['groups'] as $group)
                    <div>
                        <p class="text-xs font-semibold text-[var(--gv-color-neutral-500)] mb-2">{{ __($group['label']) }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($group['children'] as $child)
                                <a href="{{ route($child['route']) }}" class="gv-mobile-chip {{ request()->routeIs($child['match'] ?? $child['route']) ? 'gv-nav-icon--active' : '' }}">
                                    {{ __($child['label']) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="gv-mobile-nav-section">
            <div class="gv-mobile-nav-title">{{ __('Utilities') }}</div>
            <div class="flex items-center gap-3">
                <a href="{{ $searchRoute }}" class="gv-icon-button" title="{{ __('Search') }}">
                    {!! $iconSvg('search') !!}
                </a>
                @foreach($navigation['secondary'] as $secondary)
                    <a href="{{ route($secondary['route']) }}"
                        class="gv-icon-button {{ $isActive($secondary) ? 'gv-icon-button--active' : '' }}"
                        title="{{ __($secondary['label']) }}">
                        {!! $iconSvg($secondary['icon'] ?? null) !!}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="gv-mobile-nav-section">
            <div class="gv-mobile-nav-title">{{ __('Account & Settings') }}</div>
            <div class="space-y-2">
                @foreach($navigation['settings'] as $settingsLink)
                    <a href="{{ route($settingsLink['route']) }}" class="block gv-mobile-chip w-full justify-between">
                        {{ __($settingsLink['label']) }}
                    </a>
                @endforeach
                @foreach($navigation['admin'] as $adminLink)
                    <a href="{{ route($adminLink['route']) }}" class="block gv-mobile-chip w-full justify-between">
                        {{ __($adminLink['label']) }}
                    </a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="gv-mobile-chip w-full justify-center text-[var(--gv-color-danger)] border-[var(--gv-color-danger)]">
                        {{ get_phrase('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

