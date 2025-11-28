@extends('layouts.app')

@section('title', 'My CVs')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/jobs">Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">CVs</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="cv-list-page">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">My CVs</h1>
        <a class="btn btn-primary" href="{{ route('cv.edit') }}">Create New CV</a>
    </div>
    <div class="row g-3">
        @forelse(($cvs ?? []) as $cv)
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="card-title mb-0">{{ $cv->name ?? 'Untitled CV' }}</h5>
                            <span class="badge bg-light text-dark">{{ $cv->updated_at?->diffForHumans() }}</span>
                        </div>
                        <p class="text-muted small mb-3">Updated {{ $cv->updated_at?->format('M d, Y') }}</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('cv.edit', $cv->id ?? null) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('cv.download', $cv->id ?? null) }}" class="btn btn-sm btn-outline-secondary">Download</a>
                            <form method="post" action="{{ route('cv.destroy', $cv->id ?? null) }}" class="ms-auto">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border">No CVs yet. Create your first one to apply faster.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
