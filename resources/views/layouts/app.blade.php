<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if(config('advertisement.enabled'))
            <meta name="gigvora-ads-api-base" content="{{ url('/api/advertisement') }}">
        @endif
        @if(config('gigvora_talent_ai.enabled'))
            <meta name="gigvora-talent-ai-api-base" content="{{ url('/api/addons/talent-ai') }}">
        @endif
        @if(config('webinar_networking_interview_podcast.enabled'))
            <meta name="gigvora-live-api-base" content="{{ url(config('webinar_networking_interview_podcast.routes.api.prefix', 'api/live')) }}">
        @endif

        <title>@yield('title', config('app.name', get_settings('system_name')))</title>
        @stack('meta')

        <!-- Fonts -->
       

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @if(config('advertisement.enabled'))
            <link rel="stylesheet" href="{{ mix('css/advertisement/addon.css') }}">
        @endif
        @stack('styles')

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @stack('head-scripts')
    </head>
    <body class="font-sans antialiased gv-body bg-[var(--gv-color-neutral-50)] text-[var(--gv-color-neutral-800)]">
        <a href="#main-content" class="gv-skip-link">{{ __('Skip to main content') }}</a>
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white/90 backdrop-blur border-b border-[var(--gv-color-border)]">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-2">
                    @yield('breadcrumbs')
                    @if(View::hasSection('page-header'))
                        @yield('page-header')
                    @elseif(isset($header))
                        {{ $header }}
                    @endif
                </div>
            </header>

            <!-- Page Content -->
            <main id="main-content" class="flex-1 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                    @hasSection('content')
                        @yield('content')
                    @elseif(isset($slot))
                        {{ $slot }}
                    @endif
                </div>
            </main>

            @include('components.utilities.chat-bubble')
        </div>
        @stack('scripts')
    </body>
</html>
