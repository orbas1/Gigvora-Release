@extends('wnip::layouts.live')

@php use Illuminate\Support\Str; @endphp

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interviewer panel') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Scoring & notes') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Capture slot scores, recommendations, and notes in real time.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    $primarySlot = $primarySlot ?? $interview->slots->sortBy('starts_at')->first();
    $criteria = $interview->metadata['criteria'] ?? [
        ['key' => 'communication', 'name' => get_phrase('Communication'), 'score' => 3, 'comment' => ''],
        ['key' => 'problem_solving', 'name' => get_phrase('Problem solving'), 'score' => 3, 'comment' => ''],
        ['key' => 'collaboration', 'name' => get_phrase('Collaboration'), 'score' => 3, 'comment' => ''],
    ];
@endphp
<div class="space-y-4" id="interviewer-panel"
    data-save-url="{{ $primarySlot ? route('wnip.interviews.score', ['interview' => $interview, 'interviewSlot' => $primarySlot]) : '' }}">
    <div class="gv-card flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                {{ optional($primarySlot?->interviewee)->name ?? get_phrase('Candidate') }}
            </h2>
            <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">
                {{ $interview->title }} •
                {{ $primarySlot?->starts_at?->format('M j • g:i A') ?? $interview->scheduled_at?->format('M j • g:i A') }}
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button class="gv-btn gv-btn-ghost" id="lock-scores">{{ get_phrase('Lock scores') }}</button>
            <button class="gv-btn gv-btn-primary" id="save-scores">{{ get_phrase('Save') }}</button>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.6fr)_320px]">
        <div class="space-y-4">
            <div class="gv-card">
                <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                    {{ get_phrase('Use this panel to monitor the interview and capture scores in real time.') }}
                </p>
            </div>

            <div class="gv-card space-y-3">
                <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Criteria & scoring') }}</h3>
                <div class="overflow-auto">
                    <table class="w-full text-sm border-collapse" id="scoring-table">
                        <thead>
                            <tr class="text-left text-[var(--gv-color-neutral-500)]">
                                <th class="py-2 pr-3">{{ get_phrase('Criteria') }}</th>
                                <th class="py-2 pr-3 w-32">{{ get_phrase('Score') }}</th>
                                <th class="py-2">{{ get_phrase('Comments') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $criterion)
                                <tr class="border-t border-[var(--gv-color-border)]" data-name="{{ $criterion['name'] }}" data-key="{{ $criterion['key'] ?? Str::slug($criterion['name']) }}">
                                    <td class="py-2 pr-3">{{ $criterion['name'] }}</td>
                                    <td class="py-2 pr-3">
                                        <select class="gv-input" name="score">
                                            @for($i=1;$i<=5;$i++)
                                                <option value="{{ $i }}" @selected(($criterion['score'] ?? 3) === $i)>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="py-2">
                                        <input class="gv-input" name="comment" value="{{ $criterion['comment'] ?? '' }}" placeholder="{{ get_phrase('Comments') }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    <label class="gv-label" for="panel-comments">{{ get_phrase('Overall comments') }}</label>
                    <textarea id="panel-comments" class="gv-input" rows="3" placeholder="{{ get_phrase('Notes visible to the panel') }}"></textarea>
                </div>
                <div>
                    <label class="gv-label">{{ get_phrase('Recommendation') }}</label>
                    <select class="gv-input" id="recommendation">
                        <option>{{ get_phrase('Hire') }}</option>
                        <option>{{ get_phrase('Hold') }}</option>
                        <option>{{ get_phrase('Reject') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <aside class="space-y-4">
            @include('wnip::components.notes_sidebar')
            <div class="gv-card" data-status role="status" aria-live="polite">
                {{ get_phrase('Scores auto-save while you edit. Locked entries are read-only.') }}
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewerScoring.js') }}"></script>
@endpush
