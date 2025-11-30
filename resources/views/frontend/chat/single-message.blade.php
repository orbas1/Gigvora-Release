@php
    $reactionOptions = collect(app(\App\Services\UtilitiesComposerAssetsService::class)->reactions() ?? [])->keyBy('type');
@endphp

@if(!empty($message))
    @foreach ($message->sortBy('id') as $chatMessage)
        @php
            $isMine = $chatMessage->sender_id === auth()->user()->id;
            $user = $isMine ? auth()->user() : \App\Models\User::find($chatMessage->sender_id);
            $attachments = $chatMessage->mediaFiles ?? collect();
            $reactions = $chatMessage->reactions ?? collect();
            $reactionCounts = $reactions
                ->groupBy(function ($reaction) {
                    return $reaction->type instanceof \BackedEnum ? $reaction->type->value : $reaction->type;
                })
                ->map->count();
            $messageId = $chatMessage->id;
            $text = trim($chatMessage->message ?? '');
            $isUrl = filter_var($text, FILTER_VALIDATE_URL);
        @endphp

        <div class="gv-chat-message {{ $isMine ? 'gv-chat-message--mine' : '' }}" id="message-{{ $messageId }}" data-gv-message-id="{{ $messageId }}">
            <div class="gv-chat-bubble">
                @if ($isUrl)
                    @php
                        $url = $text;
                        $path = parse_url($url, PHP_URL_PATH);
                        $segments = $path ? explode('/', trim($path, '/')) : [];
                        $shared_id = end($segments);
                    @endphp
                    @if($segments && count($segments) >= 2 && $segments[count($segments)-2] === 'post')
                        <iframe src="{{ route('custom.shared.post.view',$shared_id) }}?shared=yes" scrolling="no" class="w-full rounded mb-2" onload="resizeIframe(this)" frameborder="0"></iframe>
                    @elseif($segments && count($segments) >= 2 && $segments[count($segments)-2] === 'product')
                        <iframe src="{{ route('single.product.iframe',$shared_id) }}?shared=yes" scrolling="no" class="w-full rounded mb-2" onload="resizeIframe(this)" frameborder="0"></iframe>
                    @endif
                    <a href="{{ $url }}" class="gv-chat-link" target="_blank" rel="noopener">{{ $url }}</a>
                @elseif($text !== '')
                    <p class="gv-chat-text">{!! nl2br(e($text)) !!}</p>
                @endif

                @if ($attachments->isNotEmpty())
                    <div class="gv-chat-attachments">
                        @foreach ($attachments as $file)
                            @if ($file->file_type === 'image')
                                <img src="{{ asset('storage/chat/images/'.$file->file_name) }}" alt="" class="gv-chat-attachment">
                            @else
                                <video class="gv-chat-attachment" controls>
                                    <source src="{{ asset('storage/chat/videos/'.$file->file_name) }}">
                                </video>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if(!empty($chatMessage->thumbsup) && empty($text))
                    <div class="gv-chat-sticker">👍</div>
                @endif
            </div>

            <div class="gv-chat-message__footer">
                <div class="gv-chat-message__meta">
                    @if($chatMessage->created_at)
                        <span class="gv-chat-timestamp">{{ $chatMessage->created_at->diffForHumans() }}</span>
                    @endif
                </div>
                <div class="gv-chat-message__reactions" id="ShowReactId_{{ $messageId }}">
                    @include('frontend.chat.chat_react', ['message' => $chatMessage, 'reactionOptions' => $reactionOptions])
                </div>
            </div>

            @if($reactionCounts->isNotEmpty())
                <div class="gv-chat-reaction-summary">
                    @foreach($reactionCounts as $type => $count)
                        @php
                            $option = $reactionOptions->get($type, []);
                            $emoji = $option['emoji'] ?? '👍';
                        @endphp
                        <span class="gv-chat-reaction-chip">{{ $emoji }} {{ $count }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
@endif

