<div class="card h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Live Chat</span>
        <span class="badge bg-secondary" id="live-attendee-count">{{ $count ?? 0 }} online</span>
    </div>
    <div class="card-body overflow-auto" style="max-height: 320px;" id="live-chat-feed">
        @forelse(($messages ?? []) as $message)
            <div class="mb-2">
                <strong>{{ $message['author'] ?? 'User' }}</strong>
                <small class="text-muted">{{ $message['time'] ?? 'Now' }}</small>
                <div>{{ $message['body'] ?? '' }}</div>
            </div>
        @empty
            <p class="text-muted">No messages yet. Say hello!</p>
        @endforelse
    </div>
    <div class="card-footer">
        <form id="live-chat-form" class="d-flex gap-2">
            <input class="form-control" name="message" placeholder="Type a message" />
            <button class="btn btn-primary" type="submit">Send</button>
        </form>
    </div>
</div>
