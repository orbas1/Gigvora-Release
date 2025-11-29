@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="mb-1">{{ $series->title }}</h1>
            <p class="text-muted">{{ $series->description }}</p>
        </div>
        <span class="badge bg-light text-dark">Episodes {{ $series->episodes->count() }}</span>
    </div>

    <div class="card shadow-sm">
        <div class="list-group list-group-flush">
            @forelse($series->episodes as $episode)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $episode->title }}</div>
                        <div class="text-muted small">{{ $episode->published_at?->toDayDateTimeString() ?? 'Draft' }}</div>
                    </div>
                    <a class="btn btn-outline-primary" href="{{ $episode->audio_path ?? '#' }}" target="_blank">Play</a>
                </div>
            @empty
                <div class="list-group-item text-muted">No episodes yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
