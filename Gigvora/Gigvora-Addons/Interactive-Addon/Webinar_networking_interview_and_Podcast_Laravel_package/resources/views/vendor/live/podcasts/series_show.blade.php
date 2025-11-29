@extends('layouts.app')

@section('title', $series['title'] ?? 'Podcast Series')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('wnip.podcasts.index') ?? '#' }}">Podcasts</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $series['title'] ?? 'Series' }}</li>
    </ol>
</nav>
@endsection

@section('content')
@php
    $series = $series ?? [
        'title' => 'Product Stories',
        'description' => 'Behind the roadmap with top PMs.',
        'host' => 'Kim',
        'episodes' => [
            ['title' => 'Episode 1', 'duration' => '32m', 'date' => 'Apr 1'],
            ['title' => 'Episode 2', 'duration' => '28m', 'date' => 'Apr 8'],
        ],
    ];
@endphp
<div class="container py-4" id="podcast-series" data-follow-url="{{ route('wnip.podcasts.follow', ['series' => $series['id'] ?? 1]) ?? '#' }}">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="d-flex gap-3 align-items-start mb-3">
                <div class="ratio ratio-1x1 bg-light rounded" style="width:120px;"></div>
                <div>
                    <h1 class="mb-1">{{ $series['title'] }}</h1>
                    <div class="text-muted mb-2">Host: {{ $series['host'] }}</div>
                    <p>{{ $series['description'] }}</p>
                    <button class="btn btn-outline-primary" id="follow-series">Follow</button>
                </div>
            </div>
            <h5>Episodes</h5>
            <ul class="list-group" id="episode-list">
                @foreach($series['episodes'] as $episode)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $episode['title'] }}</div>
                            <div class="text-muted small">{{ $episode['date'] }} • {{ $episode['duration'] }}</div>
                        </div>
                        <a href="{{ route('wnip.podcasts.episode', ['episode' => $loop->index + 1]) ?? '#' }}" class="btn btn-outline-primary btn-sm">Play</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-lg-4">
            @include('vendor.live.components.notes_sidebar')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
