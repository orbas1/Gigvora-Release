@extends('layouts.app')

@section('title', $episode['title'] ?? 'Episode Player')

@section('content')
@php
    $episode = $episode ?? [
        'title' => 'Episode 1: Shipping fast',
        'description' => 'Conversation on agile shipping.',
        'guests' => 'Kim & Lee',
        'show_notes' => 'Links and resources go here.'
    ];
@endphp
<div class="container py-4" id="podcast-episode" data-episode-id="{{ $episode['id'] ?? 1 }}">
    <div class="card mb-3">
        <div class="card-body">
            <h1 class="h4">{{ $episode['title'] }}</h1>
            <p class="text-muted mb-3">Guests: {{ $episode['guests'] }}</p>
            <div class="d-flex align-items-center gap-3 mb-3">
                <button class="btn btn-primary" id="audio-toggle">Play</button>
                <input type="range" class="form-range" id="audio-progress" min="0" max="100" value="0">
                <div class="text-muted" id="audio-time">00:00 / 00:00</div>
                <select class="form-select w-auto" id="audio-speed">
                    <option value="0.75">0.75x</option>
                    <option value="1" selected>1x</option>
                    <option value="1.25">1.25x</option>
                    <option value="1.5">1.5x</option>
                </select>
            </div>
            <p>{{ $episode['description'] }}</p>
            <div class="mt-3">
                <h6>Show notes</h6>
                <p class="mb-0">{{ $episode['show_notes'] }}</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h6>More episodes</h6>
            <ul class="list-unstyled mb-0" id="related-episodes">
                <li><a href="#">Episode 2: Growth</a></li>
                <li><a href="#">Episode 3: Hiring</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
