@extends('layouts.app')

@section('title', 'Webinar Recordings')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('wnip.webinars.index') ?? '#' }}">Webinars</a></li>
        <li class="breadcrumb-item active" aria-current="page">Recordings</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="recordings-catalogue">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Recordings</h1>
        <div class="d-flex gap-2">
            <input type="search" class="form-control" placeholder="Search recordings" name="search">
            <select class="form-select" name="category">
                <option value="">All categories</option>
                <option>Engineering</option>
                <option>Product</option>
            </select>
        </div>
    </div>
    @php
        $recordings = $recordings ?? [
            ['title' => 'Designing with AI', 'date' => 'Mar 3', 'duration' => '54m', 'tags' => 'Design, AI'],
            ['title' => 'Scaling APIs', 'date' => 'Mar 10', 'duration' => '48m', 'tags' => 'Backend'],
        ];
    @endphp
    <div class="list-group">
        @foreach($recordings as $recording)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold">{{ $recording['title'] }}</div>
                    <div class="text-muted small">{{ $recording['date'] }} • {{ $recording['duration'] }} • {{ $recording['tags'] }}</div>
                </div>
                <a class="btn btn-outline-primary" href="{{ route('wnip.webinars.recording', ['recording' => $loop->index + 1]) ?? '#' }}">Watch Replay</a>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
