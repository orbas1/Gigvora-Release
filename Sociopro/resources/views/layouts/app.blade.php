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

        <title>@yield('title', config('app.name', get_settings('system_name')))</title>
        @stack('meta')

        <!-- Fonts -->
       

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @stack('styles')

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @stack('head-scripts')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('breadcrumbs')
                    @if(View::hasSection('page-header'))
                        @yield('page-header')
                    @elseif(isset($header))
                        {{ $header }}
                    @endif
                </div>
            </header>

            <!-- Page Content -->
            <main>
                @hasSection('content')
                    @yield('content')
                @elseif(isset($slot))
                    {{ $slot }}
                @endif
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
