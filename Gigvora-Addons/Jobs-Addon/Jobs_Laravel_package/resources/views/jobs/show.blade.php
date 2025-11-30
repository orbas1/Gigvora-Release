@extends('layouts.app')

@section('content')
@php($jobDetailAd = config('advertisement.enabled') ? app(\App\Services\AdvertisementSurfaceService::class)->forSlot('jobs_detail') : null)
<div class="container space-y-4">
    <h1>{{ $job->title }}</h1>
    <p class="text-muted">{{ $job->company->name ?? '' }} — {{ $job->location }}</p>
    <div class="mb-3">{!! nl2br(e($job->description)) !!}</div>
    @includeWhen($jobDetailAd, 'advertisement::components.ad_banner', ['ad' => $jobDetailAd])
    <form method="post" action="{{ route('applications.store') }}">
        @csrf
        <input type="hidden" name="job_id" value="{{ $job->id }}">
        <button class="btn btn-success">{{ __('jobs::jobs.apply') }}</button>
    </form>
</div>
@endsection
