@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-1">Interviews</h1>
            <p class="text-muted mb-0">Upcoming and in-progress interviews.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('wnip.interviews.index', ['create' => 1]) }}">Schedule Interview</a>
    </div>

    <form method="get" class="card p-3 shadow-sm mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Search</label>
                <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}" placeholder="Title">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-outline-secondary" type="submit">Filter</button>
            </div>
        </div>
    </form>

    <div class="row g-3">
        @forelse($interviews as $interview)
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $interview->title }}</h5>
                                <p class="text-muted mb-1">{{ $interview->scheduled_at?->toDayDateTimeString() }}</p>
                                <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($interview->description, 120) }}</p>
                            </div>
                            <span class="badge bg-light text-dark">{{ $interview->is_panel ? 'Panel' : 'Single' }}</span>
                        </div>
                        <a class="btn btn-outline-primary" href="{{ route('wnip.interviews.show', $interview) }}">Open</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No interviews scheduled.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $interviews->links() }}</div>
</div>
@endsection
