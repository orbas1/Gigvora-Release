<div class="gv-card h-full flex flex-col">
    <div class="flex items-center justify-between mb-3">
        <span class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">
            {{ get_phrase('Live chat') }}
        </span>
        <span class="gv-pill">
            <span id="live-attendee-count">{{ $count ?? 0 }}</span> {{ get_phrase('online') }}
        </span>
    </div>
    <div class="flex-1 overflow-auto space-y-3 pr-1" style="max-height: 320px;" id="live-chat-feed">
        @forelse(($messages ?? []) as $message)
            <div class="space-y-1">
                <div class="flex items-center gap-2 text-sm">
                    <strong class="text-[var(--gv-color-neutral-900)]">{{ $message['author'] ?? get_phrase('User') }}</strong>
                    <span class="text-xs text-[var(--gv-color-neutral-500)]">{{ $message['time'] ?? get_phrase('Now') }}</span>
                </div>
                <div class="text-sm text-[var(--gv-color-neutral-700)]">{{ $message['body'] ?? '' }}</div>
            </div>
        @empty
            <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">{{ get_phrase('No messages yet. Say hello!') }}</p>
        @endforelse
    </div>
    <div class="pt-4">
        <form id="live-chat-form" class="flex gap-2">
            <input class="gv-input flex-1" name="message" placeholder="{{ get_phrase('Type a message') }}" />
            <button class="gv-btn gv-btn-primary" type="submit">{{ get_phrase('Send') }}</button>
        </form>
    </div>
</div>
