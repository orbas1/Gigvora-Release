<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ get_phrase('Dashboard') }}
                    </x-nav-link>

                    @if(config('advertisement.enabled') && Route::has('advertisement.dashboard'))
                        <x-nav-link :href="route('advertisement.dashboard')" :active="request()->routeIs('advertisement.*')">
                            <span class="inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 4.5A1.5 1.5 0 014.5 3h11A1.5 1.5 0 0117 4.5v11a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 013 15.5v-11zM5 5v10h10V5H5zm2 2h2v6H7V7zm4 2h2v4h-2V9z" />
                                </svg>
                                {{ __('Ads Manager') }}
                            </span>
                        </x-nav-link>
                    @endif

                    @if(config('gigvora_talent_ai.enabled'))
                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <span class="inline-flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M4 3a1 1 0 00-1 1v12a1 1 0 001.555.832L8 14.5l3.445 2.332A1 1 0 0013 16V4a1 1 0 00-1.555-.832L8 5.5 4.555 3.168A1 1 0 004 3z" />
                                        </svg>
                                        {{ __('Talent & AI') }}
                                    </span>
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if(config('gigvora_talent_ai.modules.headhunters.enabled') && Route::has('addons.talent_ai.headhunters.dashboard'))
                                    <x-dropdown-link :href="route('addons.talent_ai.headhunters.dashboard')">
                                        {{ __('Headhunters') }}
                                    </x-dropdown-link>
                                @endif
                                @if(config('gigvora_talent_ai.modules.launchpad.enabled') && Route::has('addons.talent_ai.launchpad.programmes.index'))
                                    <x-dropdown-link :href="route('addons.talent_ai.launchpad.programmes.index')">
                                        {{ __('Experience Launchpad') }}
                                    </x-dropdown-link>
                                @endif
                                @if(config('gigvora_talent_ai.modules.ai_workspace.enabled') && Route::has('addons.talent_ai.ai_workspace.index'))
                                    <x-dropdown-link :href="route('addons.talent_ai.ai_workspace.index')">
                                        {{ __('AI Workspace') }}
                                    </x-dropdown-link>
                                @endif
                                @if(config('gigvora_talent_ai.modules.volunteering.enabled') && Route::has('addons.talent_ai.volunteering.opportunities.index'))
                                    <x-dropdown-link :href="route('addons.talent_ai.volunteering.opportunities.index')">
                                        {{ __('Volunteering') }}
                                    </x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ get_phrase('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ get_phrase('Dashboard') }}
                    </x-responsive-nav-link>

                    @if(config('advertisement.enabled') && Route::has('advertisement.dashboard'))
                        <x-responsive-nav-link :href="route('advertisement.dashboard')" :active="request()->routeIs('advertisement.*')">
                            {{ __('Ads Manager') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(config('gigvora_talent_ai.enabled'))
                        @if(config('gigvora_talent_ai.modules.headhunters.enabled') && Route::has('addons.talent_ai.headhunters.dashboard'))
                            <x-responsive-nav-link :href="route('addons.talent_ai.headhunters.dashboard')" :active="request()->routeIs('addons.talent_ai.headhunters.*')">
                                {{ __('Headhunters') }}
                            </x-responsive-nav-link>
                        @endif
                        @if(config('gigvora_talent_ai.modules.launchpad.enabled') && Route::has('addons.talent_ai.launchpad.programmes.index'))
                            <x-responsive-nav-link :href="route('addons.talent_ai.launchpad.programmes.index')" :active="request()->routeIs('addons.talent_ai.launchpad.*')">
                                {{ __('Experience Launchpad') }}
                            </x-responsive-nav-link>
                        @endif
                        @if(config('gigvora_talent_ai.modules.ai_workspace.enabled') && Route::has('addons.talent_ai.ai_workspace.index'))
                            <x-responsive-nav-link :href="route('addons.talent_ai.ai_workspace.index')" :active="request()->routeIs('addons.talent_ai.ai_workspace.*')">
                                {{ __('AI Workspace') }}
                            </x-responsive-nav-link>
                        @endif
                        @if(config('gigvora_talent_ai.modules.volunteering.enabled') && Route::has('addons.talent_ai.volunteering.opportunities.index'))
                            <x-responsive-nav-link :href="route('addons.talent_ai.volunteering.opportunities.index')" :active="request()->routeIs('addons.talent_ai.volunteering.*')">
                                {{ __('Volunteering') }}
                            </x-responsive-nav-link>
                        @endif
                    @endif
                </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ get_phrase('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
