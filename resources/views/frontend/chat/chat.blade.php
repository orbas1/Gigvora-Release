@php
    $composerAssets = $composerAssets ?? [
        'reactions' => [],
        'emoji_packs' => [],
        'sticker_packs' => [],
        'gif' => ['enabled' => false, 'endpoint' => null],
    ];
@endphp

<div class="gv-chat-thread__container">
    @if(!empty($reciver_data))
        <header class="gv-chat-thread__header">
            <div class="flex items-center gap-3">
                <div class="gv-chat-thread__avatar">
                    <img src="{{ get_user_image($reciver_data->photo,'optimized') }}" alt="{{ $reciver_data->name }}">
                    @if ($reciver_data->isOnline())
                        <span class="gv-chat-thread__status" aria-label="{{ get_phrase('Online') }}"></span>
                    @endif
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-[var(--gv-color-neutral-900)] mb-0">
                        {{ $reciver_data->name }}
                    </h2>
                    @if ($reciver_data->isOnline())
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('Active now') }}</p>
                    @else
                        <p class="gv-muted text-sm mb-0">
                            {{ \Carbon\Carbon::parse($reciver_data->lastActive)->diffForHumans() }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('user.profile.view',$reciver_data->id) }}" class="gv-btn gv-btn-ghost">
                    <i class="fa-regular fa-user me-1"></i> {{ get_phrase('View profile') }}
                </a>
            </div>
        </header>

        <div class="gv-chat-thread__messages" id="messageShowDiv">
            <div id="message_body">
                @include('frontend.chat.single-message')
            </div>
        </div>

        @php
            $product_url = session()->has('product_ref_id') ? url('/')."/product/view/".session('product_ref_id') : null;
            Session::forget('product_ref_id');
        @endphp

        <div class="gv-chat-composer"
            data-gv-composer='@json($composerAssets)'
            data-gv-gif-endpoint="{{ $composerAssets['gif']['enabled'] && $composerAssets['gif']['endpoint'] ? route('api.utilities.composer.gifs') : '' }}">
            <div class="gv-chat-composer__toolbar">
                <button type="button" class="gv-btn gv-btn-icon" data-gv-composer-toggle="emoji" aria-label="{{ get_phrase('Insert emoji') }}">
                    <i class="fa-regular fa-face-smile"></i>
                </button>
                <button type="button" class="gv-btn gv-btn-icon" data-gv-composer-toggle="sticker" aria-label="{{ get_phrase('Insert sticker') }}">
                    <i class="fa-regular fa-note-sticky"></i>
                </button>
                <button type="button" class="gv-btn gv-btn-icon" data-gv-composer-toggle="gif" aria-label="{{ get_phrase('Search GIFs') }}">
                    <i class="fa-regular fa-file-video"></i>
                </button>
                <button type="button" class="gv-btn gv-btn-icon" data-gv-attachment-trigger aria-label="{{ get_phrase('Attach files') }}">
                    <i class="fa-solid fa-paperclip"></i>
                </button>
            </div>
            <form class="ajaxForm gv-chat-composer__form" id="chatMessageFieldForm" action="{{ route('chat.save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="reciver_id" value="{{ $reciver_data->id }}">
                @if ($product != null)
                    <input type="hidden" name="product_id" value="{{ $product }}">
                @endif
                <input type="hidden" name="thumbsup" value="0" id="ChatthumbsUpInput">
                <textarea class="gv-chat-composer__input" name="message" id="ChatmessageField" rows="1" placeholder="{{ get_phrase('Type a message') }}">{{ $product_url }}</textarea>
                <div class="gv-chat-composer__actions">
                    <input type="file" name="multiple_files[]" id="chatAttachmentInput" class="sr-only" multiple>
                    <button type="button" class="gv-btn gv-btn-text" data-gv-attachment-trigger>
                        <i class="fa-solid fa-paperclip me-1"></i>{{ get_phrase('Add files') }}
                    </button>
                    <button class="gv-btn gv-btn-primary no-processing no-uploading" id="ChatsentButton">
                        <i class="fa-solid fa-paper-plane me-1"></i>{{ get_phrase('Send') }}
                    </button>
                </div>
                <button type="reset" id="messageResetBox" class="hidden">{{ get_phrase('Reset') }}</button>
            </form>
            <div class="gv-composer-panels">
                <div class="gv-composer-panel" data-gv-composer-panel="emoji">
                    @forelse($composerAssets['emoji_packs'] as $pack)
                        <div class="gv-composer-group">
                            <p class="gv-composer-group__label">{{ $pack['label'] }}</p>
                            <div class="gv-composer-grid">
                                @foreach($pack['items'] as $emoji)
                                    <button type="button" class="gv-composer-emoji" data-gv-insert="{{ $emoji }}">{{ $emoji }}</button>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('Emoji packs unavailable.') }}</p>
                    @endforelse
                </div>
                <div class="gv-composer-panel" data-gv-composer-panel="sticker">
                    @forelse($composerAssets['sticker_packs'] as $pack)
                        <div class="gv-composer-group">
                            <p class="gv-composer-group__label">{{ $pack['label'] }}</p>
                            <div class="gv-composer-grid">
                                @foreach($pack['items'] as $sticker)
                                    <button type="button" class="gv-composer-sticker" data-gv-insert="{{ $sticker['emoji'] }}">
                                        <span class="text-2xl">{{ $sticker['emoji'] }}</span>
                                        <span class="text-xs">{{ $sticker['label'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('Sticker packs unavailable.') }}</p>
                    @endforelse
                </div>
                <div class="gv-composer-panel" data-gv-composer-panel="gif">
                    <form class="gv-composer-gif-search" data-gv-gif-search>
                        <input type="search" placeholder="{{ get_phrase('Search GIFs') }}" aria-label="{{ get_phrase('Search GIFs') }}">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                    <div class="gv-composer-gif-results" data-gv-gif-results>
                        <p class="gv-muted text-sm mb-0">{{ get_phrase('Enter a keyword to load GIF suggestions.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="gv-empty min-h-[320px] flex items-center justify-center">
            <p class="mb-0">{{ get_phrase('Select a conversation to start messaging.') }}</p>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        "use strict";

        $(document).ready(function(){
            const messageWrapper = document.getElementById('messageShowDiv');
            if (messageWrapper) {
                messageWrapper.scrollTop = messageWrapper.scrollHeight;
            }

            window.ajaxCallForDataLoad = function () {
                const currentURL = window.location.href;
                const id = currentURL.substring(currentURL.lastIndexOf('/') + 1);
                $.ajax({
                    type : 'get',
                    url : '{{ url('/chat/inbox/load/data/ajax/') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                    },
                    data:{'id':id},
                    success:function(response){
                        distributeServerResponse(response);
                        if(messageWrapper){
                            messageWrapper.scrollTop = messageWrapper.scrollHeight;
                        }
                    }
                });
            };

            setInterval(window.ajaxCallForDataLoad, 4000);

            $('#chatSearch').on('keyup', function () {
                const value = $(this).val();
                $.ajax({
                    type : 'get',
                    url : '{{ url('/chat/profile/search/') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                    },
                    data:{'search':value},
                    success:function(response){
                        $('#chatFriendList').html(response);
                    }
                });
            });

            $('#chatMessageFieldForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this)[0];
                const formData = new FormData(form);

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        distributeServerResponse(response);
                        $('#ChatmessageField').val('');
                        form.reset();
                        setTimeout(() => {
                            if (messageWrapper) {
                                messageWrapper.scrollTop = messageWrapper.scrollHeight;
                            }
                        }, 300);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endpush

