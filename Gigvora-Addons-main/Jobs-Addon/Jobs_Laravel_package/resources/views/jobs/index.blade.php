@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ __('jobs::jobs.title') }}</h1>
    <div class="row">
        @foreach($jobs as $job)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $job->title }}</h5>
                        <p class="card-text text-muted">{{ $job->company->name ?? '' }}</p>
                        <p class="card-text">{{ $job->location }}</p>
                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-primary">{{ __('jobs::jobs.apply') }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
