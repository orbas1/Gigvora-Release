@extends('layouts.app')

@section('content')
@php($jobsAd = config('advertisement.enabled') ? app(\App\Services\AdvertisementSurfaceService::class)->forSlot('jobs') : null)
<div class="container space-y-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <p class="gv-eyebrow mb-1">{{ __('Jobs') }}</p>
            <h1 class="mb-0 gv-heading text-2xl">{{ __('jobs::jobs.title') }}</h1>
        </div>
    </div>

    @includeWhen($jobsAd, 'advertisement::components.ad_banner', ['ad' => $jobsAd])

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
