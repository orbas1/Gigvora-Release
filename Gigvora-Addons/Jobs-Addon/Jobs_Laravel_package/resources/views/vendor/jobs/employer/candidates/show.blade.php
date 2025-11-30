@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('title', get_phrase('Candidate'))

@section('page-header')
    <div class="flex items-center gap-3">
        <div class="h-14 w-14 rounded-full bg-[var(--gv-color-neutral-100)] border border-[var(--gv-color-border)] flex items-center justify-center text-[var(--gv-color-neutral-500)] font-semibold uppercase">
            {{ Str::substr($candidate->name, 0, 2) }}
        </div>
        <div>
            <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ $candidate->name }}</h1>
            <p class="gv-muted text-sm mb-0">{{ $candidate->headline }} · {{ $candidate->location }}</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid gap-4 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]" id="candidate-detail" data-candidate-id="{{ $candidate->id ?? '' }}">
        <div class="space-y-4">
            <section class="gv-card space-y-2">
                <h2 class="text-lg font-semibold mb-0">{{ get_phrase('Application') }}</h2>
                <p class="gv-muted text-sm mb-0">{{ get_phrase('Applied :date', ['date' => optional($candidate->applied_at)->format('M d, Y')]) }}</p>
                <p class="text-sm text-[var(--gv-color-neutral-800)] mb-0">
                    {{ get_phrase('Stage') }}:
                    <span class="gv-chip gv-chip-muted">{{ ucfirst($candidate->stage ?? 'applied') }}</span>
                </p>
                @if($candidate->cv_url)
                    <a href="{{ $candidate->cv_url }}" target="_blank" class="gv-btn gv-btn-ghost gv-btn-sm mt-2">
                        {{ get_phrase('View CV') }}
                    </a>
                @endif
            </section>

            <section class="gv-card space-y-3">
                <h2 class="text-lg font-semibold mb-0">{{ get_phrase('Screening answers') }}</h2>
                @forelse($candidate->screening_answers ?? [] as $answer)
                    <div>
                        <p class="font-medium text-[var(--gv-color-neutral-900)] mb-1">{{ $answer['question'] }}</p>
                        <p class="gv-muted text-sm mb-0">{{ $answer['answer'] }}</p>
                    </div>
                @empty
                    <p class="gv-muted text-sm mb-0">{{ get_phrase('No screening responses.') }}</p>
                @endforelse
            </section>

            <section class="gv-card space-y-3">
                <h2 class="text-lg font-semibold mb-0">{{ get_phrase('Notes & tags') }}</h2>
                <textarea class="gv-input min-h-[120px]" id="candidate-note" placeholder="{{ get_phrase('Add a note') }}"></textarea>
                <button class="gv-btn gv-btn-ghost gv-btn-sm w-fit" id="save-note">{{ get_phrase('Save note') }}</button>
                <div class="flex flex-wrap gap-2" id="candidate-tags">
                    @foreach($candidate->tags ?? [] as $tag)
                        <span class="gv-chip">{{ $tag }}</span>
                    @endforeach
                </div>
                <input type="text" class="gv-input" id="new-tag" placeholder="{{ get_phrase('Add tag and press enter') }}">
            </section>
        </div>

        <aside class="space-y-4">
            <div class="gv-card space-y-3">
                <h2 class="text-lg font-semibold mb-0">{{ get_phrase('Actions') }}</h2>
                <select class="gv-input" id="stage-select">
                    @foreach(['applied','screening','shortlisted','interview','offer','hired','rejected'] as $stage)
                        <option value="{{ $stage }}" @selected(($candidate->stage ?? '') === $stage)>{{ ucfirst($stage) }}</option>
                    @endforeach
                </select>
                <button class="gv-btn gv-btn-primary w-full" id="update-stage">{{ get_phrase('Move to stage') }}</button>
                <button class="gv-btn gv-btn-ghost w-full" id="invite-interview">{{ get_phrase('Invite to interview') }}</button>
                <button class="gv-btn gv-btn-ghost w-full text-[var(--gv-color-danger-600)]" id="reject-candidate">{{ get_phrase('Reject') }}</button>
            </div>
            <a href="{{ $candidate->profile_url }}" target="_blank" class="gv-btn gv-btn-ghost w-full justify-center">
                {{ get_phrase('View Gigvora profile') }}
            </a>
        </aside>
    </div>
@endsection