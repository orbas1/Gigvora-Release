<div class="card mb-2 candidate-card" data-candidate-id="{{ $candidate->id ?? '' }}">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="fw-semibold">{{ $candidate->name ?? $candidate->title ?? 'Candidate' }}</div>
                <div class="text-muted small">{{ $candidate->headline ?? ($candidate->years_experience ?? '') . ' yrs experience' }}</div>
            </div>
            <div class="d-flex gap-1">
                <span class="badge bg-light text-dark">{{ $candidate->tag ?? 'Talent' }}</span>
                <button class="btn btn-outline-secondary btn-sm move-stage" data-candidate-id="{{ $candidate->id ?? '' }}">Move</button>
            </div>
        </div>
        @if(isset($candidate->notes))
            <p class="text-muted small mb-0 mt-2">{{ $candidate->notes }}</p>
        @endif
    </div>
</div>
