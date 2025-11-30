@extends('layouts.app')

@section('title', get_phrase('Job wizard'))

@section('page-header')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Create a new job') }}</h1>
        <a href="{{ route('employer.jobs.index') }}" class="gv-btn gv-btn-ghost">{{ get_phrase('Back to jobs') }}</a>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('resources/js/jobs/jobPostWizard.js') }}"></script>
@endpush

@section('content')
    <div id="job-post-wizard" class="space-y-4" data-job-id="{{ $job->id ?? '' }}">
        <div class="bg-[var(--gv-color-neutral-100)] rounded-full h-2 overflow-hidden">
            <div class="h-full bg-[var(--gv-color-primary-500)] transition-all duration-300" id="job-wizard-progress" style="width: 20%;"></div>
        </div>
        <form id="job-wizard-form" method="post" action="{{ route('employer.jobs.store') }}" class="space-y-6">
            @csrf
            <section class="gv-card wizard-step space-y-4" data-step="1">
                <div>
                    <h2 class="text-lg font-semibold mb-1">{{ get_phrase('Job basics') }}</h2>
                    <p class="gv-muted text-sm mb-0">{{ get_phrase('Define the title, location, and working style.') }}</p>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="gv-label">{{ get_phrase('Job title') }}</label>
                        <input class="gv-input" name="title" required>
                    </div>
                    <div class="space-y-2">
                        <label class="gv-label">{{ get_phrase('Location') }}</label>
                        <input class="gv-input" name="location" placeholder="{{ get_phrase('Remote, Lagos, Berlin...') }}">
                    </div>
                    <div class="space-y-2">
                        <label class="gv-label">{{ get_phrase('Employment type') }}</label>
                        <select class="gv-input" name="employment_type">
                            <option value="full-time">{{ get_phrase('Full-time') }}</option>
                            <option value="part-time">{{ get_phrase('Part-time') }}</option>
                            <option value="contract">{{ get_phrase('Contract') }}</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="gv-label">{{ get_phrase('Workplace type') }}</label>
                        <select class="gv-input" name="workplace_type">
                            <option value="on-site">{{ get_phrase('On-site') }}</option>
                            <option value="hybrid">{{ get_phrase('Hybrid') }}</option>
                            <option value="remote">{{ get_phrase('Remote') }}</option>
                        </select>
                    </div>
                </div>
            </section>

            <section class="gv-card wizard-step hidden space-y-4" data-step="2">
                <div>
                    <h2 class="text-lg font-semibold mb-1">{{ get_phrase('Description') }}</h2>
                    <p class="gv-muted text-sm mb-0">{{ get_phrase('Paint a clear picture of responsibilities and expectations.') }}</p>
                </div>
                <textarea class="gv-input min-h-[220px]" name="description" required placeholder="{{ get_phrase('Describe the role...') }}"></textarea>
            </section>

            <section class="gv-card wizard-step hidden space-y-4" data-step="3">
                <div>
                    <h2 class="text-lg font-semibold mb-1">{{ get_phrase('Compensation & visibility') }}</h2>
                    <p class="gv-muted text-sm mb-0">{{ get_phrase('Share salary guidance and choose when the role should expire.') }}</p>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="gv-label">{{ get_phrase('Minimum salary') }}</label>
                        <input class="gv-input" type="number" step="100" min="0" name="salary_min">
                    </div>
                    <div class="space-y-2">
                        <label class="gv-label">{{ get_phrase('Maximum salary') }}</label>
                        <input class="gv-input" type="number" step="100" min="0" name="salary_max">
                    </div>
                    <div class="space-y-2">
                        <label class="gv-label">{{ get_phrase('Currency') }}</label>
                        <input class="gv-input" name="currency" value="USD">
                    </div>
                    <div class="space-y-2">
                        <label class="gv-label">{{ get_phrase('Expiration date') }}</label>
                        <input class="gv-input" type="date" name="expires_at">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="gv-label">{{ get_phrase('Status') }}</label>
                    <select class="gv-input" name="status">
                        <option value="draft">{{ get_phrase('Save as draft') }}</option>
                        <option value="published">{{ get_phrase('Publish immediately') }}</option>
                    </select>
                </div>
            </section>

            <section class="gv-card wizard-step hidden space-y-4" data-step="4">
                <div>
                    <h2 class="text-lg font-semibold mb-1">{{ get_phrase('Review & publish') }}</h2>
                    <p class="gv-muted text-sm mb-0">{{ get_phrase('Double‑check the details before launching the role.') }}</p>
                </div>
                <p class="gv-muted text-sm mb-0">{{ get_phrase('Once published, candidates can find this role via Jobs search, feed cards, and Utilities alerts.') }}</p>
            </section>

            <div class="flex items-center justify-between">
                <button type="button" class="gv-btn gv-btn-ghost" id="job-prev-step">{{ get_phrase('Back') }}</button>
                <button type="button" class="gv-btn gv-btn-primary" id="job-next-step">{{ get_phrase('Next') }}</button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/jobPostWizard.js') }}"></script>
@endpush
