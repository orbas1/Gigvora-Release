<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', get_settings('system_name')) }}</title>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <script src="{{ asset('js/app.js') }}" defer></script>
        @stack('head')
    </head>
    <body class="font-sans antialiased gv-body bg-[var(--gv-color-neutral-50)] text-[var(--gv-color-neutral-800)]">
        <a href="#guest-content" class="gv-skip-link">{{ __('Skip to main content') }}</a>
        <div id="guest-content" class="min-h-screen">
            {{ $slot }}
        </div>
        @stack('scripts')
    </body>
</html>
