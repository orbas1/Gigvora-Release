@extends('layouts.app', ['include_menu' => true])

@section('content')
    <div class="gv-shell" aria-labelledby="freelance-page-heading">
        <header class="gv-shell-header">
            <div class="gv-shell-header-inner">
                <div class="gv-logo-mark">GV</div>
                <div class="gv-shell-header-title">
                    <span class="gv-shell-header-title-main">{{ __('Freelance workspace') }}</span>
                    <span class="gv-shell-header-title-sub">{{ __('Gigs · Projects · Escrow · Disputes') }}</span>
                </div>
            </div>
        </header>

        <div class="gv-shell-main">
            <aside class="gv-sidebar">
                @include('components.navigation.freelance-menu')
            </aside>

            <section class="gv-main" id="freelance-page-heading">
                @include('components.utilities.quick-tools', ['context' => 'freelance'])
                @yield('freelance-content')
            </section>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/freelance/app.css') }}">
@endpush

@push('scripts')
    <script defer src="{{ mix('js/freelance/app.js') }}"></script>
@endpush
