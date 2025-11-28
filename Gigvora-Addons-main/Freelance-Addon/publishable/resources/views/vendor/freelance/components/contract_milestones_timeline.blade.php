<div class="timeline">
    @forelse($milestones ?? [] as $milestone)
        <div class="timeline-item d-flex gap-3 mb-3 align-items-start">
            <div class="timeline-marker rounded-circle {{ $milestone['status'] === 'released' ? 'bg-success' : 'bg-secondary' }}" style="width:14px;height:14px;"></div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="fw-semibold">{{ $milestone['title'] }}</div>
                        <small class="text-muted">Due {{ $milestone['due_date'] ?? 'TBD' }}</small>
                    </div>
                    <div class="fw-bold">{{ $milestone['amount'] ?? '$0' }}</div>
                </div>
                <p class="mb-1 small text-muted">Status: {{ ucfirst($milestone['status'] ?? 'pending') }}</p>
                @if(!empty($milestone['actions']))
                    <div class="d-flex gap-2">
                        @foreach($milestone['actions'] as $action)
                            <button class="btn btn-sm {{ $action['style'] ?? 'btn-outline-primary' }}" data-action="{{ $action['name'] }}" data-id="{{ $milestone['id'] ?? '' }}">{{ $action['label'] }}</button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted">No milestones yet.</p>
    @endforelse
</div>
