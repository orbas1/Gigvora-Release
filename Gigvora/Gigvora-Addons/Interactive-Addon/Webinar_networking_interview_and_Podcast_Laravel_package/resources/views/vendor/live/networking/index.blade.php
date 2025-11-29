@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <div>
            <h1 class="mb-1">Networking Sessions</h1>
            <p class="text-muted mb-0">Speed networking and curated meetups.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('wnip.networking.index', ['create' => 1]) }}">Host Networking</a>
    </div>

    <form method="get" class="card p-3 shadow-sm mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Search</label>
                <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}" placeholder="Title or topic">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-outline-secondary" type="submit">Filter</button>
            </div>
        </div>
    </form>

    <div class="row g-3">
        @forelse($sessions as $session)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title mb-0">{{ $session->title }}</h5>
                            <span class="badge bg-light text-dark">{{ ucfirst($session->status) }}</span>
                        </div>
                        <p class="text-muted small mb-2">{{ optional($session->starts_at)->toDayDateTimeString() }}</p>
                        <p class="text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($session->description, 120) }}</p>
                        <div class="small text-muted mb-2">{{ $session->participants->count() }} registered</div>
                        <a class="btn btn-outline-primary w-100" href="{{ route('wnip.networking.show', $session) }}">View</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No networking sessions yet.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $sessions->links() }}</div>
</div>
@endsection
