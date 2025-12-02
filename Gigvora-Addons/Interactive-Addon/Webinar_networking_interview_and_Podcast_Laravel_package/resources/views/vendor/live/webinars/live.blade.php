@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Webinar Live Room') }}</p>
        <h1 class="live-header__title">{{ $webinar->title ?? __('Live webinar') }}</h1>
        <p class="live-header__subtitle">{{ __('Engage with attendees in real time, manage chat, and share resources.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    $attendeeCount = $webinar->registrations()->count();
@endphp
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_360px]">
    <div class="space-y-4">
        <div class="gv-card p-0 overflow-hidden">
            <div class="bg-[var(--gv-color-neutral-100)] min-h-[320px] flex items-center justify-center text-sm text-[var(--gv-color-neutral-500)]"
                id="webinar-video-container">
                {{ get_phrase('Embed your streaming widget here.') }}
            </div>
            <div class="border-t border-[var(--gv-color-border)] p-4">
                @include('wnip::components.host_tools_toolbar')
            </div>
        </div>

        @include('wnip::components.live_chat_panel', ['messages' => $messages ?? [], 'count' => $webinar->registrations()->count()])
    </div>

    <aside class="space-y-4">
        <div class="gv-card space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                        {{ $webinar->title }}
                    </p>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                        {{ $webinar->starts_at?->format('M j • g:i A') }}
                    </p>
                </div>
                <span class="gv-pill gv-pill--danger">{{ get_phrase('Live') }}</span>
            </div>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ $webinar->waiting_room_message ?? get_phrase('Stay engaged and participate in the Q&A.') }}
            </p>
            <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                {{ trans_choice('{0}No attendees yet|{1}1 attendee|[2,*]:count attendees', $attendeeCount, ['count' => $attendeeCount]) }}
            </p>
        </div>

        <div class="gv-card space-y-2">
            <h3 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Streaming details') }}
            </h3>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ get_phrase('Provider: :provider', ['provider' => $webinar->stream_provider ?? get_phrase('Custom')]) }}
            </p>
            @if($webinar->rtmp_endpoint)
                <p class="break-all text-xs text-[var(--gv-color-neutral-500)] mb-0">
                    {{ get_phrase('RTMP: :endpoint', ['endpoint' => $webinar->rtmp_endpoint]) }}
                </p>
            @endif
        </div>

        <div class="gv-card space-y-2">
            <h3 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Engagement & moderation') }}</h3>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">{{ trans_choice('{0}No registrants yet|{1}1 registered attendee|[2,*]:count registered attendees', $attendeeCount, ['count' => $attendeeCount]) }}</p>
            <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">{{ get_phrase('Use the host toolbar to pin Q&A, drop resources, and pause chat if needed.') }}</p>
        </div>

        @include('wnip::components.notes_sidebar')
    </aside>
</div>
@endsection
