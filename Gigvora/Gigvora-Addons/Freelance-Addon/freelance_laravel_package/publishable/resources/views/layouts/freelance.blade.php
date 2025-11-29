@extends('layouts.app', ['include_menu' => true])

@section('content')
    <main class="tk-scetiondb freelance-shell" aria-labelledby="freelance-page-heading">
        @include('components.navigation.freelance-menu')

        <div class="freelance-shell__body">
            <h1 id="freelance-page-heading" class="sr-only">{{ __('Freelance workspace') }}</h1>
            @yield('freelance-content')
        </div>
    </main>
@endsection

@push('styles')
    @vite('resources/css/freelance/navigation.css')
@endpush
