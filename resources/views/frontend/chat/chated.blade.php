<div class="space-y-4">
    <div class="gv-chat-sidebar__header">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Conversations') }}</p>
            <h2 class="text-xl font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Inbox') }}
            </h2>
        </div>
        <span class="gv-chip gv-chip-muted">
            {{ number_format($previousChatList->count()) }}
        </span>
    </div>
    <div class="gv-chat-sidebar__search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="search" id="chatSearch" placeholder="{{ get_phrase('Search conversations') }}">
    </div>
    <div class="gv-chat-sidebar__list" id="chatFriendList">
        @if(!empty($reciver_data))
            @include('frontend.chat.single-chated')
        @else
            <div class="gv-empty">
                <p class="mb-0">{{ get_phrase('Select a conversation to get started.') }}</p>
            </div>
        @endif
    </div>
</div>
