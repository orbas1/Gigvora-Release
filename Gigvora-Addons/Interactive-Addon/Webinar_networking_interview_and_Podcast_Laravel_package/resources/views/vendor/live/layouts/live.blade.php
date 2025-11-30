@extends('layouts.app')

@section('title', trim(($title ?? __('Live & Events')) . ' • ' . config('app.name')))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/live/app.css') }}">
@endpush

@section('content')
    <div class="live-shell max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @include('wnip::components.nav')

        <div class="live-shell__body">
            @hasSection('live-header')
                <header class="live-header">
                    @yield('live-header')
                </header>
            @endif

            @yield('live-content')
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="{{ mix('js/live/app.js') }}"></script>
@endpush

