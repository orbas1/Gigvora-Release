@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-1">Podcasts</h1>
            <p class="text-muted mb-0">Browse series and latest episodes.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('wnip.podcasts.index', ['create' => 1]) }}">Create Series</a>
    </div>

    <form method="get" class="card p-3 shadow-sm mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Search</label>
                <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}" placeholder="Series title">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-outline-secondary" type="submit">Filter</button>
            </div>
        </div>
    </form>

    <div class="row g-3">
        @forelse($series as $item)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($item->description, 100) }}</p>
                        <div class="small text-muted mb-2">Episodes: {{ $item->episodes->count() }}</div>
                        <a class="btn btn-outline-primary w-100" href="{{ route('wnip.podcasts.series', $item) }}">Open Series</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No podcast series found.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $series->links() }}</div>
</div>
@endsection
