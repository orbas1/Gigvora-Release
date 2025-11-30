@php
    $features = config('pro_network_utilities_security_analytics.features', []);
    $chatEnabled = ($features['chat_enhancements'] ?? false) && auth()->check();
    $utilitiesEnabled = ($features['utilities_quick_tools'] ?? false) || ($features['utilities_notifications_center'] ?? false) || ($features['utilities_bookmarks'] ?? false) || ($features['utilities_calendar'] ?? false);
    $shouldRender = $chatEnabled || $utilitiesEnabled;
    $utilitiesService = app(\App\Services\UtilitiesQuickToolsService::class);
    $bubbleContext = strtolower($utilitiesContext ?? $utilitiesService->detectContext());
@endphp

@if ($shouldRender)
    @once
        @push('scripts')
            <script src="{{ mix('js/utilities/bubble.js') }}" defer></script>
        @endpush
    @endonce

    <div
        class="gv-utilities-bubble"
        data-gv-utilities-bubble
        data-inbox-url="{{ route('messages.index') }}"
        data-endpoints='@json([
            'conversations' => $chatEnabled ? url('api/pro-network/chat/conversations') : null,
            'requests' => $chatEnabled ? url('api/pro-network/chat/requests') : null,
        ])'
        data-quick-tools-url="{{ ($features['utilities_quick_tools'] ?? false) ? route('api.utilities.quick-tools') : '' }}"
        data-quick-tools-context="{{ $bubbleContext }}"
        data-empty-conversations="{{ get_phrase('Start a conversation from your connections list.') }}"
        data-empty-requests="{{ get_phrase('No new message requests.') }}"
        data-empty-quick-tools="{{ get_phrase('No quick tools available for this surface yet.') }}"
    >
        <button type="button" class="gv-utilities-bubble__toggle" data-role="toggle" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 5.25h9A1.5 1.5 0 0 1 18 6.75v8.25a1.5 1.5 0 0 1-1.5 1.5h-2.25l-2.25 2.25-2.25-2.25H7.5A1.5 1.5 0 0 1 6 15V6.75a1.5 1.5 0 0 1 1.5-1.5Z" />
            </svg>
            <span>{{ get_phrase('Utilities') }}</span>
            <span class="gv-utilities-bubble__badge" data-role="bubble-count" hidden>0</span>
        </button>

        <div class="gv-utilities-bubble__panel" data-role="bubble-panel" hidden>
            <div class="gv-utilities-bubble__header">
                <div>
                    <h3>{{ get_phrase('Utilities & Chat') }}</h3>
                    <p>{{ get_phrase('Conversations, requests, and quick links follow you everywhere.') }}</p>
                </div>
                <button type="button" class="gv-icon-button" data-action="close" aria-label="{{ get_phrase('Close utilities bubble') }}">
                    <svg viewBox="0 0 20 20" aria-hidden="true">
                        <path d="m6 6 8 8M6 14 14 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            @if ($chatEnabled)
                <div class="gv-utilities-bubble__section">
                    <div class="gv-utilities-bubble__section-header">
                        <span>{{ get_phrase('Recent chats') }}</span>
                        <button class="gv-mobile-chip" type="button" data-action="open-inbox">
                            {{ get_phrase('Open inbox') }}
                        </button>
                    </div>
                    <div class="gv-utilities-bubble__list" data-role="conversations" data-inbox="{{ route('messages.index') }}">
                        <div class="gv-utilities-bubble__empty">{{ get_phrase('Loading conversations...') }}</div>
                    </div>
                </div>

                <div class="gv-utilities-bubble__section">
                    <div class="gv-utilities-bubble__section-header">
                        <span>{{ get_phrase('Message requests') }}</span>
                    </div>
                    <div class="gv-utilities-bubble__list" data-role="requests" data-inbox="{{ route('messages.index') }}">
                        <div class="gv-utilities-bubble__empty">{{ get_phrase('Loading message requests...') }}</div>
                    </div>
                </div>
            @endif

            <div class="gv-utilities-bubble__section" data-role="quick-tools-section" hidden>
                <div class="gv-utilities-bubble__section-header">
                    <span>{{ get_phrase('Context quick tools') }}</span>
                </div>
                <div class="gv-utilities-bubble__list" data-role="quick-tools">
                    <div class="gv-utilities-bubble__empty">{{ get_phrase('Loading quick tools...') }}</div>
                </div>
            </div>

            @if ($utilitiesEnabled)
                <div class="gv-utilities-bubble__section">
                    <div class="gv-utilities-bubble__section-header">
                        <span>{{ get_phrase('Quick utilities') }}</span>
                    </div>
                    <div class="gv-utilities-bubble__quick-links">
                        @if ($features['utilities_notifications_center'] ?? false)
                            <a href="{{ route('notifications.index') }}" class="gv-mobile-chip">
                                {{ get_phrase('Notifications') }}
                            </a>
                        @endif
                        @if ($features['utilities_bookmarks'] ?? false)
                            <a href="{{ route('saved.index') }}" class="gv-mobile-chip">
                                {{ get_phrase('Saved items') }}
                            </a>
                        @endif
                        @if ($features['utilities_calendar'] ?? false)
                            <a href="{{ route('calendar.index') }}" class="gv-mobile-chip">
                                {{ get_phrase('Schedule') }}
                            </a>
                        @endif
                        <a href="{{ route('messages.index') }}" class="gv-mobile-chip">
                            {{ get_phrase('Inbox') }}
                        </a>
                    </div>
                </div>
            @endif

            <p class="gv-utilities-bubble__error" data-role="bubble-error"></p>
        </div>
    </div>
@endif

