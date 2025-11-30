<article class="gv-card candidate-card space-y-2" data-candidate-id="{{ $candidate->id ?? '' }}">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ $candidate->name ?? get_phrase('Candidate') }}</p>
            <p class="gv-muted text-xs mb-0">{{ $candidate->headline ?? get_phrase(':years yrs experience', ['years' => $candidate->years_experience ?? 0]) }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if(!empty($candidate->tag))
                <span class="gv-chip gv-chip-muted">{{ $candidate->tag }}</span>
            @endif
            <button class="gv-btn gv-btn-ghost gv-btn-sm move-stage" data-candidate-id="{{ $candidate->id ?? '' }}">
                {{ get_phrase('Move') }}
            </button>
        </div>
    </div>
    @if(!empty($candidate->notes))
        <p class="gv-muted text-xs mb-0">{{ $candidate->notes }}</p>
    @endif
</article>