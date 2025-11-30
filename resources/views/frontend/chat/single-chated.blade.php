@forelse ($previousChatList as $thread)
    @php
        $msgStarterId = $thread->sender_id == auth()->user()->id ? $thread->reciver_id : $thread->sender_id;
        if ($msgStarterId == auth()->user()->id) {
            continue;
        }
        $user = \App\Models\User::find($msgStarterId);
        if (!$user) {
            continue;
        }
        $lastMsg = \App\Models\Chat::where('message_thrade', $thread->id)->orderBy('id', 'desc')->first();
        $unreadMsgCount = \App\Models\Chat::where('message_thrade', $thread->id)->where('reciver_id', auth()->user()->id)->where('read_status', '0')->count();
        $isActive = isset($reciver_data) && $reciver_data && $reciver_data->id === $user->id;
    @endphp
    <a href="{{ route('chat', $user->id) }}" class="gv-conversation-row {{ $isActive ? 'gv-conversation-row--active' : '' }}">
        <div class="gv-conversation-row__avatar">
            <img src="{{ get_user_image($user->photo,'optimized') }}" alt="{{ $user->name }}">
            @if ($user->isOnline())
                <span class="gv-conversation-row__status" aria-label="{{ get_phrase('Online') }}"></span>
            @endif
        </div>
        <div class="gv-conversation-row__body">
            <p class="gv-conversation-row__name">{{ $user->name }}</p>
            <p class="gv-conversation-row__preview">
                @if(!empty($lastMsg?->thumbsup))
                    <i class="fa-solid fa-thumbs-up me-1"></i>{{ get_phrase('Reacted') }}
                @else
                    {{ isset($lastMsg->message) ? ellipsis(strip_tags($lastMsg->message), 42) : get_phrase('New conversation') }}
                @endif
            </p>
        </div>
        @if ($unreadMsgCount > 0)
            <span class="gv-badge gv-badge-primary">{{ $unreadMsgCount }}</span>
        @endif
    </a>
@empty
    <div class="gv-empty">
        <p class="mb-0">{{ get_phrase('No conversations yet.') }}</p>
    </div>
@endforelse