@extends('layouts.app')

@section('title', get_phrase('ATS board'))

@section('page-header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ $job->title }}</h1>
            <p class="gv-muted text-sm mb-0">{{ get_phrase('Drag candidates between stages to keep everyone aligned.') }}</p>
        </div>
        <input type="search" class="gv-input w-full lg:w-64" id="candidate-search" placeholder="{{ get_phrase('Search candidates') }}">
    </div>
@endsection

@section('content')
    <div id="ats-board" class="overflow-x-auto" data-job-id="{{ $job->id }}">
        <div class="flex gap-4 min-w-max">
            @foreach(['applied' => get_phrase('Applied'), 'screening' => get_phrase('Screening'), 'shortlisted' => get_phrase('Shortlisted'), 'interview' => get_phrase('Interview'), 'offer' => get_phrase('Offer'), 'hired' => get_phrase('Hired'), 'rejected' => get_phrase('Rejected')] as $stage => $label)
                <div class="w-72" data-stage="{{ $stage }}">
                    @include('vendor.jobs.components.ats_stage_column', ['stage' => $stage, 'label' => $label, 'candidates' => $pipelines[$stage] ?? []])
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('resources/js/jobs/atsBoard.js') }}"></script>
@endpush