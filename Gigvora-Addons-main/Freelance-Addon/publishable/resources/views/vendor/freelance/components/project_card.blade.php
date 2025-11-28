<div class="card mb-3 project-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5 class="card-title mb-1">{{ $project['title'] ?? 'Project Title' }}</h5>
                <p class="text-muted mb-2">{{ $project['summary'] ?? 'Short project description.' }}</p>
                <div class="d-flex gap-2 align-items-center small text-muted">
                    <span>{{ $project['budget'] ?? '$0' }}</span>
                    <span>•</span>
                    <span>{{ $project['proposals_count'] ?? 0 }} proposals</span>
                    <span>•</span>
                    <span>{{ $project['posted'] ?? 'Just now' }}</span>
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-info text-dark">{{ ucfirst($project['status'] ?? 'open') }}</span>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ $project['view_url'] ?? '#' }}" class="btn btn-outline-primary btn-sm">View</a>
            @isset($project['cta'])
                <a href="{{ $project['cta']['url'] }}" class="btn btn-primary btn-sm">{{ $project['cta']['label'] }}</a>
            @endisset
        </div>
    </div>
</div>
