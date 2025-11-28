@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-1">{{ $interview->title }}</h1>
            <p class="text-muted">{{ $interview->scheduled_at?->toDayDateTimeString() }} • {{ $interview->duration_minutes }} mins</p>
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5>Description</h5>
                    <p class="text-muted">{!! nl2br(e($interview->description)) !!}</p>
                    <h6 class="mt-3">Slots</h6>
                    <ul class="list-group list-group-flush">
                        @forelse($interview->slots as $slot)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $slot->starts_at?->toDayDateTimeString() }}</div>
                                    <div class="text-muted small">{{ $slot->interviewer_id }} ➜ {{ $slot->interviewee_id }}</div>
                                </div>
                                @auth
                                    <form method="post" action="{{ route('wnip.interviews.score', [$interview, $slot]) }}" class="d-flex gap-2 align-items-center">
                                        @csrf
                                        <input type="hidden" name="criteria[communication]" value="5">
                                        <input type="hidden" name="scores[communication]" value="5">
                                        <button class="btn btn-sm btn-outline-primary" type="submit">Score</button>
                                    </form>
                                @endauth
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No slots scheduled.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Host</div>
                    <p class="text-muted">{{ optional($interview->host)->name ?? 'Host' }}</p>
                    <a class="btn btn-outline-secondary w-100" href="{{ route('wnip.interviews.waiting', $interview) }}">Waiting Room</a>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">Scores</div>
                <div class="list-group list-group-flush">
                    @forelse($interview->scores as $score)
                        <div class="list-group-item">
                            <div class="fw-semibold">Interviewer {{ $score->interviewer_id }}</div>
                            <div class="text-muted small">{{ json_encode($score->scores) }}</div>
                        </div>
                    @empty
                        <div class="list-group-item text-muted">No scores yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
