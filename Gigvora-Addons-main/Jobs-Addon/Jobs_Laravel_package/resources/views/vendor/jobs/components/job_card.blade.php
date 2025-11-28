<div class="card job-card mb-3" data-job-id="{{ $job->id ?? '' }}">
    <div class="card-body d-flex justify-content-between align-items-start">
        <div class="me-3">
            <div class="d-flex align-items-center mb-1">
                <div class="placeholder rounded bg-light me-2" style="width:40px;height:40px;"></div>
                <div>
                    <h5 class="card-title mb-0">{{ $job->title ?? 'Job title' }}</h5>
                    <div class="text-muted small">{{ $job->company->name ?? 'Company' }}</div>
                </div>
            </div>
            <div class="text-muted small">{{ $job->location ?? 'Remote' }} · {{ $job->salary_label ?? 'Competitive' }}</div>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach(($job->tag_list ?? ['Remote']) as $tag)
                    <span class="badge bg-light text-dark">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @if($showActions ?? false)
            <div class="d-flex flex-column align-items-end gap-2">
                <a href="{{ route('jobs.show', $job->id ?? null) }}" class="btn btn-sm btn-outline-primary">View details</a>
                <button class="btn btn-light btn-sm save-job" data-job-id="{{ $job->id ?? '' }}">❤</button>
            </div>
        @endif
    </div>
</div>
