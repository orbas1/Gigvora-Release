@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('title', $job->title ?? get_phrase('Job'))

@section('page-header')
    <div class="flex flex-col gap-2">
        <p class="gv-eyebrow">{{ get_phrase('Role detail') }}</p>
        <h1 class="text-3xl font-semibold text-[var(--gv-color-neutral-900)]">{{ $job->title ?? get_phrase('Job title') }}</h1>
        <p class="gv-muted text-sm">
            {{ optional($job->company)->name ?? get_phrase('Company') }} · {{ $job->location ?? get_phrase('Remote') }} ·
            {{ $job->employment_type ?? get_phrase('Full-time') }}
        </p>
    </div>
@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]" id="job-detail-page" data-job-id="{{ $job->id ?? '' }}">
        <div class="space-y-4">
            <article class="gv-card space-y-4">
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <span class="gv-chip">{{ ucfirst($job->workplace_type ?? get_phrase('Flexible')) }}</span>
                    <span class="gv-chip">{{ $job->salary_label ?? get_phrase('Competitive salary') }}</span>
                    <span class="gv-chip gv-chip-muted">{{ get_phrase('Status') }}: {{ ucfirst($job->status ?? 'open') }}</span>
                </div>
                <div class="prose prose-sm max-w-none text-[var(--gv-color-neutral-800)] leading-relaxed">
                    {!! nl2br(e($job->description ?? get_phrase('Role description coming soon.'))) !!}
                </div>
            </article>

            @if(($job->screeningQuestions ?? collect())->isNotEmpty())
                <section class="gv-card space-y-3">
                    <h2 class="text-lg font-semibold text-[var(--gv-color-neutral-900)] mb-0">
                        {{ get_phrase('Screening questions') }}
                    </h2>
                    <ul class="list-disc ps-5 space-y-2 text-sm text-[var(--gv-color-neutral-700)]">
                        @foreach($job->screeningQuestions as $question)
                            <li>{{ $question->question }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section class="space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold mb-0">{{ get_phrase('Similar roles') }}</h2>
                    <a href="{{ route('jobs.index', ['keywords' => $job->title]) }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                        {{ get_phrase('See all') }}
                    </a>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    @forelse($similarJobs ?? [] as $similar)
                        @include('vendor.jobs.components.job_card', ['job' => $similar, 'showActions' => true])
                    @empty
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('No similar roles available right now.') }}</p>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="space-y-4">
            @include('components.utilities.quick-tools', ['context' => 'job_detail', 'variant' => 'compact'])

            <div class="gv-card space-y-4">
                <div class="flex items-start gap-3">
                    <div class="h-12 w-12 rounded-full bg-[var(--gv-color-neutral-100)] border border-[var(--gv-color-border)] flex items-center justify-center text-[var(--gv-color-neutral-500)] font-semibold uppercase">
                        {{ Str::substr(optional($job->company)->name, 0, 2) }}
                    </div>
                    <div>
                        <h3 class="text-base font-semibold mb-1">{{ optional($job->company)->name ?? get_phrase('Company') }}</h3>
                        <p class="gv-muted text-sm mb-1">{{ optional($job->company)->location ?? $job->location ?? get_phrase('Global') }}</p>
                        @if(optional($job->company)->website)
                            <a href="{{ $job->company->website }}" target="_blank" class="text-sm text-[var(--gv-color-primary-600)]">
                                {{ get_phrase('Visit website') }}
                            </a>
                        @endif
                    </div>
                </div>

            <div class="flex flex-col gap-2">
                <a class="gv-btn gv-btn-primary w-full" href="{{ route('jobs.apply', $job->id) }}">
                    <i class="fa-solid fa-paper-plane me-2"></i>{{ get_phrase('Apply now') }}
                </a>
                <button class="gv-btn gv-btn-ghost save-job w-full" data-job-id="{{ $job->id ?? '' }}">
                    <i class="fa-regular fa-bookmark me-2"></i>{{ get_phrase('Save role') }}
                </button>
            </div>
            <p class="gv-muted text-xs mb-0">{{ get_phrase('Use your Gigvora profile, CV templates, or upload a tailored resume.') }}</p>
        </div>
    </aside>
</div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('resources/js/jobs/jobDetail.js') }}"></script>
@endpush
