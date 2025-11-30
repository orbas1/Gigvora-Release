@extends('layouts.app')

@section('title', get_phrase('Apply') . ' – ' . ($job->title ?? get_phrase('Role')))

@section('page-header')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Application wizard') }}</p>
            <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">
                {{ get_phrase('Apply to :role', ['role' => $job->title]) }}
            </h1>
        </div>
        <span class="gv-chip gv-chip-muted">{{ optional($job->company)->name }}</span>
    </div>
@endsection

@section('content')
    <div id="job-apply-wizard" class="grid gap-6 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]" data-job-id="{{ $job->id ?? '' }}">
        <div class="space-y-4">
            <div class="bg-[var(--gv-color-neutral-100)] rounded-full h-2 overflow-hidden">
                <div id="apply-progress" class="h-full bg-[var(--gv-color-primary-500)] transition-all duration-300" style="width: 20%;"></div>
            </div>

            <form id="application-form" method="post" action="{{ route('jobs.apply.submit', $job->id) }}" class="space-y-6">
                @csrf
                <input type="hidden" name="job_id" value="{{ $job->id }}">
                <input type="hidden" name="candidate_id" value="{{ auth()->id() }}">

                <section class="gv-card step space-y-4" data-step="1">
                    <div>
                        <h2 class="text-lg font-semibold mb-1">{{ get_phrase('CV & Experience') }}</h2>
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('Pick a saved CV from Gigvora or connect a new one.') }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="gv-label" for="cv_template_id">{{ get_phrase('Choose a CV') }}</label>
                        <select class="gv-input" name="cv_template_id" id="cv_template_id" required>
                            <option value="">{{ get_phrase('Select a CV template') }}</option>
                            @foreach(($cvs ?? []) as $cv)
                                <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </section>

                <section class="gv-card step hidden space-y-4" data-step="2">
                    <div>
                        <h2 class="text-lg font-semibold mb-1">{{ get_phrase('Cover letter') }}</h2>
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('Let the hiring team know why you stand out.') }}</p>
                    </div>
                    <textarea class="gv-input min-h-[180px]" name="notes" placeholder="{{ get_phrase('Write your cover letter...') }}"></textarea>
                    <button type="button" class="gv-btn gv-btn-ghost gv-btn-sm" id="generate-cover-letter">
                        <i class="fa-solid fa-wand-magic-sparkles me-2"></i>{{ get_phrase('Generate starter copy') }}
                    </button>
                </section>

                <section class="gv-card step hidden space-y-4" data-step="3">
                    <div>
                        <h2 class="text-lg font-semibold mb-1">{{ get_phrase('Screening questions') }}</h2>
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('Answer honestly to help recruiters assess fit.') }}</p>
                    </div>
                    @forelse(($screeningQuestions ?? []) as $question)
                        <div class="space-y-2">
                            <input type="hidden" name="answers[{{ $loop->index }}][screening_question_id]" value="{{ $question->id }}">
                            <label class="gv-label">{{ $question->question }}</label>
                            @if($question->type === 'multiple_choice')
                                <select class="gv-input" name="answers[{{ $loop->index }}][answer]">
                                    @foreach($question->options ?? [] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @elseif($question->type === 'boolean')
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="gv-radio" name="answers[{{ $loop->index }}][answer]" value="yes">
                                        <span>{{ get_phrase('Yes') }}</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" class="gv-radio" name="answers[{{ $loop->index }}][answer]" value="no">
                                        <span>{{ get_phrase('No') }}</span>
                                    </label>
                                </div>
                            @else
                                <textarea class="gv-input" rows="3" name="answers[{{ $loop->index }}][answer]"></textarea>
                            @endif
                        </div>
                    @empty
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('No screening questions for this role.') }}</p>
                    @endforelse
                </section>

                <section class="gv-card step hidden space-y-4" data-step="4">
                    <div>
                        <h2 class="text-lg font-semibold mb-1">{{ get_phrase('Review & submit') }}</h2>
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('Confirm the details below and submit your application.') }}</p>
                    </div>
                    <label class="inline-flex items-start gap-2 text-sm">
                        <input type="checkbox" class="gv-checkbox mt-1" value="1" id="consentCheck" required>
                        <span>{{ get_phrase('I consent to sharing my information with :company', ['company' => optional($job->company)->name ?? get_phrase('the employer')]) }}</span>
                    </label>
                    <button class="gv-btn gv-btn-primary" type="submit">{{ get_phrase('Submit application') }}</button>
                </section>

                <div class="flex items-center justify-between">
                    <button type="button" class="gv-btn gv-btn-ghost" id="prev-step">{{ get_phrase('Back') }}</button>
                    <button type="button" class="gv-btn gv-btn-primary" id="next-step">{{ get_phrase('Next') }}</button>
                </div>
            </form>
        </div>

        <aside class="gv-card space-y-2">
            <h3 class="text-lg font-semibold mb-0">{{ get_phrase('Summary') }}</h3>
            <p class="text-sm text-[var(--gv-color-neutral-700)] mb-1">{{ $job->title }}</p>
            <p class="gv-muted text-sm mb-2">{{ optional($job->company)->name }} · {{ $job->location }}</p>
            <ul class="space-y-1 text-sm text-[var(--gv-color-neutral-700)]">
                <li>{{ get_phrase('Workplace') }}: {{ $job->workplace_type ?? get_phrase('Flexible') }}</li>
                <li>{{ get_phrase('Employment type') }}: {{ $job->employment_type ?? get_phrase('Full-time') }}</li>
                <li>{{ get_phrase('Salary') }}: {{ $job->salary_label ?? get_phrase('Competitive') }}</li>
            </ul>
        </aside>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('resources/js/jobs/jobApplyWizard.js') }}"></script>
@endpush