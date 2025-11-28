@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $job->title }}</h1>
    <p class="text-muted">{{ $job->company->name ?? '' }} — {{ $job->location }}</p>
    <div class="mb-3">{!! nl2br(e($job->description)) !!}</div>
    <form method="post" action="{{ route('applications.store') }}">
        @csrf
        <input type="hidden" name="job_id" value="{{ $job->id }}">
        <button class="btn btn-success">{{ __('jobs::jobs.apply') }}</button>
    </form>
</div>
@endsection
