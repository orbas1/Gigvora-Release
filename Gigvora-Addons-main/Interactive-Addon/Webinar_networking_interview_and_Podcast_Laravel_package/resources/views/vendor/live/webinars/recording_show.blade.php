@extends('layouts.app')

@section('title', 'Replay')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('wnip.webinars.recordings') ?? '#' }}">Recordings</a></li>
        <li class="breadcrumb-item active" aria-current="page">Replay</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="recording-player">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="ratio ratio-16x9 bg-dark text-white d-flex align-items-center justify-content-center mb-3" id="recording-video">
                <div>Recording player</div>
            </div>
            <div class="d-flex gap-2 mb-3">
                <button class="btn btn-outline-secondary" data-speed="0.75">0.75x</button>
                <button class="btn btn-outline-secondary" data-speed="1" class="active">1x</button>
                <button class="btn btn-outline-secondary" data-speed="1.25">1.25x</button>
                <button class="btn btn-outline-secondary" data-speed="1.5">1.5x</button>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5>Chapters</h5>
                    <ul class="list-unstyled" id="recording-chapters">
                        <li><a href="#" data-seek="0">Intro (0:00)</a></li>
                        <li><a href="#" data-seek="300">Demo (5:00)</a></li>
                        <li><a href="#" data-seek="1800">Q&A (30:00)</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Resources</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#">Slides</a></li>
                        <li><a href="#">Notes</a></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5>More from this series</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#">Episode 1</a></li>
                        <li><a href="#">Episode 2</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
