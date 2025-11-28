<div class="ats-stage" data-stage="{{ $stage }}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-uppercase text-muted mb-0">{{ $label ?? ucfirst($stage) }}</h6>
        <span class="badge bg-light text-dark">{{ count($candidates ?? []) }}</span>
    </div>
    <div class="stage-dropzone" data-stage="{{ $stage }}">
        @foreach(($candidates ?? []) as $candidate)
            @include('vendor.jobs.components.candidate_card', ['candidate' => $candidate])
        @endforeach
    </div>
</div>
