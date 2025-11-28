@extends('layouts.app')

@section('title', 'Interviewer Panel')

@section('content')
@php
    $interview = $interview ?? [
        'candidate' => 'Jamie Doe',
        'role' => 'Senior Engineer',
        'time' => 'May 7, 10:00',
        'criteria' => [
            ['name' => 'Communication', 'score' => 3, 'comment' => 'Clear answers'],
            ['name' => 'Problem Solving', 'score' => 4, 'comment' => 'Strong approach'],
            ['name' => 'Collaboration', 'score' => 3, 'comment' => 'Team-oriented'],
        ],
    ];
@endphp
<div class="container py-4" id="interviewer-panel" data-save-url="{{ route('wnip.interviews.score', ['interview' => $interview['id'] ?? 1]) ?? '#' }}">
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-1">{{ $interview['candidate'] }}</h1>
                <div class="text-muted">{{ $interview['role'] }} • {{ $interview['time'] }}</div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" id="lock-scores">Lock scores</button>
                <button class="btn btn-primary" id="save-scores">Save</button>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="interviewer-tabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#scoring" type="button" role="tab">Criteria & Scoring</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab">Notes</button></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">Use this panel to monitor the interview and capture scores in real time.</p>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="scoring" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle" id="scoring-table">
                            <thead>
                                <tr><th>Criteria</th><th style="width: 140px;">Score</th><th>Comments</th></tr>
                            </thead>
                            <tbody>
                                @foreach($interview['criteria'] as $criterion)
                                    <tr data-name="{{ $criterion['name'] }}">
                                        <td>{{ $criterion['name'] }}</td>
                                        <td>
                                            <select class="form-select form-select-sm" name="score">
                                                @for($i=1;$i<=5;$i++)
                                                    <option value="{{ $i }}" @selected($criterion['score'] === $i)>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                        <td><input class="form-control form-control-sm" name="comment" value="{{ $criterion['comment'] }}" placeholder="Comments"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Recommendation</label>
                        <select class="form-select" id="recommendation">
                            <option>Hire</option>
                            <option>Hold</option>
                            <option>Reject</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="notes" role="tabpanel">
            @include('vendor.live.components.notes_sidebar')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewerScoring.js') }}"></script>
@endpush
