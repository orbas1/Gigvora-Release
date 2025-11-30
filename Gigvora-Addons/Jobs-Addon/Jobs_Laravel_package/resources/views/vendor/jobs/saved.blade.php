@extends('layouts.app')

@section('title', get_phrase('Saved jobs'))

@section('page-header')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Job tracker') }}</p>
            <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Saved jobs') }}</h1>
        </div>
        <span class="gv-chip gv-chip-muted">{{ $savedJobs->total() }} {{ get_phrase('saved roles') }}</span>
    </div>
@endsection

@section('content')
    <div id="saved-jobs-list" class="space-y-3">
        @forelse($savedJobs as $bookmark)
            @include('vendor.jobs.components.job_card', ['job' => $bookmark->job, 'showActions' => true])
        @empty
            <div class="gv-card">
                <p class="gv-muted mb-0">{{ get_phrase('You have not saved any jobs yet. Use the bookmark button on a role to revisit it here.') }}</p>
            </div>
        @endforelse
    </div>
    @include('vendor.jobs.components.pagination', ['paginator' => $savedJobs])
@endsection