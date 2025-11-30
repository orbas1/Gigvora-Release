@extends('layouts.app')

@php $user_info = Auth()->user() @endphp

@section('title', get_phrase('Inbox & Chat'))

@section('page-header')
    <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="gv-eyebrow">{{ get_phrase('Messaging') }}</p>
            <h1 class="text-3xl font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Inbox & chat') }}
            </h1>
            <p class="gv-muted mt-1 max-w-2xl">
                {{ get_phrase('Keep conversations aligned with Utilities quick tools, reactions, and composer enhancements.') }}
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('utilities.notifications.index') }}" class="gv-btn gv-btn-ghost">
                <i class="fa-regular fa-bell me-1"></i> {{ get_phrase('Notifications center') }}
            </a>
            <a href="{{ route('utilities.calendar.index') }}" class="gv-btn gv-btn-primary">
                <i class="fa-regular fa-calendar me-1"></i> {{ get_phrase('Open calendar') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="gv-chat-layout">
        <aside class="gv-card gv-chat-sidebar">
            @include('frontend.chat.chated')
        </aside>
        <section class="gv-card gv-chat-thread">
            @include('frontend.chat.chat')
        </section>
    </div>
    @include('frontend.modal')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/fontawesome/all.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/frontend/js/jquery-3.6.0.min.js') }}"></script>
    @include('frontend.common_scripts')
    @include('frontend.toaster')
    @include('frontend.initialize')
    <script src="{{ mix('js/utilities/composer.js') }}" defer></script>
@endpush

