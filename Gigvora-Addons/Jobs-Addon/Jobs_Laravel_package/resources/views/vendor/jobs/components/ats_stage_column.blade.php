<div class="space-y-3">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold text-[var(--gv-color-neutral-600)] uppercase tracking-wide mb-0">{{ $label ?? ucfirst($stage) }}</h3>
        <span class="gv-chip gv-chip-muted">{{ count($candidates ?? []) }}</span>
    </div>
    <div class="stage-dropzone min-h-[120px] space-y-3" data-stage="{{ $stage }}">
        @foreach($candidates ?? [] as $candidate)
            @include('vendor.jobs.components.candidate_card', ['candidate' => $candidate])
        @endforeach
    </div>
</div>