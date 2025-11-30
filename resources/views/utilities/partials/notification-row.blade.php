@php
    $actor = optional($notification->getUserData);
    $actorName = $actor?->name ?? get_phrase('Gigvora member');
    $avatar = get_user_image($notification->sender_user_id ?? auth()->id(), 'optimized');
    $timestamp = optional($notification->created_at)
        ? $notification->created_at->timezone($timezone)->diffForHumans()
        : '';
    $icon = 'fa-regular fa-bell';
    $message = get_phrase('You have a new activity update.');
    $actions = [];
    $payload = $notification->data ?? [];
    $state = $notification->status === '0' ? 'unread' : 'read';
    $typeLabel = match ($notification->type) {
        'event', 'event_invitation_accept' => get_phrase('Events'),
        'fundraiser', 'fundraiser_request_accept' => get_phrase('Fundraisers'),
        'group', 'group_invitation_accept' => get_phrase('Groups'),
        'profile', 'friend_request_accept' => get_phrase('Connections'),
        'job_interview', 'job_interview_employer', 'job_application_status', 'job_application_status_employer' => get_phrase('Jobs & Interviews'),
        'interactive_interview' => get_phrase('Live & Interactive'),
        default => get_phrase('Alerts'),
    };
    $dismissUrl = $state === 'unread' ? route('utilities.notifications.read', $notification) : null;

    switch ($notification->type) {
        case 'event':
            $icon = 'fa-solid fa-calendar';
            $eventTitle = optional($notification->getEventData)->title ?? get_phrase('your event');
            $message = '<strong>'.e($actorName).'</strong> '.get_phrase('invited you to attend').' <strong>'.e($eventTitle).'</strong>';
            $actions = [
                [
                    'label' => get_phrase('Accept'),
                    'route' => route('accept.event.request.from.notification', ['id' => $notification->sender_user_id, 'event_id' => $notification->event_id]),
                    'variant' => 'primary',
                ],
                [
                    'label' => get_phrase('Decline'),
                    'route' => route('decline.event.request.from.notification', ['id' => $notification->sender_user_id, 'event_id' => $notification->event_id]),
                    'variant' => 'ghost',
                ],
            ];
            break;
        case 'fundraiser':
            $icon = 'fa-solid fa-hand-holding-heart';
            $fundraiserTitle = optional($notification->getFundraiserData)->title ?? get_phrase('a campaign');
            $message = '<strong>'.e($actorName).'</strong> '.get_phrase('invited you to support').' <strong>'.e($fundraiserTitle).'</strong>';
            $actions = [
                [
                    'label' => get_phrase('Accept'),
                    'route' => route('accept.fundraiser.request.from.notification', [
                        'id' => $notification->sender_user_id,
                        'fundraiser_id' => $notification->fundraiser_id,
                    ]),
                    'variant' => 'primary',
                ],
                [
                    'label' => get_phrase('Decline'),
                    'route' => route('decline.fundraiser.request.from.notification', [
                        'id' => $notification->sender_user_id,
                        'fundraiser_id' => $notification->fundraiser_id,
                    ]),
                    'variant' => 'ghost',
                ],
            ];
            break;
        case 'group':
            $icon = 'fa-solid fa-users';
            $groupTitle = optional($notification->getGroupData)->title ?? get_phrase('a group');
            $message = '<strong>'.e($actorName).'</strong> '.get_phrase('invited you to join').' <strong>'.e($groupTitle).'</strong>';
            $actions = [
                [
                    'label' => get_phrase('Accept'),
                    'route' => route('accept.group.request.from.notification', [
                        'id' => $notification->sender_user_id,
                        'group_id' => $notification->group_id,
                    ]),
                    'variant' => 'primary',
                ],
                [
                    'label' => get_phrase('Decline'),
                    'route' => route('decline.group.request.from.notification', [
                        'id' => $notification->sender_user_id,
                        'group_id' => $notification->group_id,
                    ]),
                    'variant' => 'ghost',
                ],
            ];
            break;
        case 'job_interview':
            $icon = 'fa-solid fa-briefcase';
            $jobTitle = $payload['job_title'] ?? get_phrase('an interview');
            $company = $payload['company_name'] ?? get_phrase('a recruiter');
            $scheduled = !empty($payload['scheduled_at'])
                ? \Carbon\Carbon::parse($payload['scheduled_at'])->timezone($timezone)->format('M d · H:i')
                : null;
            $status = ucfirst($payload['status'] ?? get_phrase('Scheduled'));
            $message = '<strong>'.e($company).'</strong> '.get_phrase('scheduled').' <strong>'.e($jobTitle).'</strong>';
            if ($scheduled) {
                $message .= ' · '.$scheduled;
            }
            $message .= ' ('.$status.')';
            $actions = [
                [
                    'label' => get_phrase('Review details'),
                    'href' => $notification->action_url ?? ($payload['cta_url'] ?? '#'),
                    'variant' => 'primary',
                    'type' => 'link',
                ],
            ];
            break;
        case 'job_interview_employer':
            $icon = 'fa-solid fa-user-tie';
            $jobTitle = $payload['job_title'] ?? get_phrase('an interview');
            $candidate = $payload['candidate_name'] ?? get_phrase('Candidate');
            $message = '<strong>'.e($candidate).'</strong> '.get_phrase('is scheduled for').' <strong>'.e($jobTitle).'</strong>';
            $actions = [
                [
                    'label' => get_phrase('Open ATS'),
                    'href' => $notification->action_url ?? ($payload['cta_url'] ?? '#'),
                    'variant' => 'primary',
                    'type' => 'link',
                ],
            ];
            break;
        case 'interactive_interview':
            $icon = 'fa-solid fa-video';
            $message = get_phrase('Your live interview is about to start');
            if (!empty($payload['scheduled_at'])) {
                $message .= ' · '.\Carbon\Carbon::parse($payload['scheduled_at'])->timezone($timezone)->diffForHumans();
            }
            $actions = [
                [
                    'label' => get_phrase('Join waiting room'),
                    'href' => $notification->action_url ?? ($payload['cta_url'] ?? '#'),
                    'variant' => 'primary',
                    'type' => 'link',
                ],
            ];
            break;
        case 'job_application_status':
            $icon = 'fa-solid fa-clipboard-check';
            $status = ucfirst($payload['status'] ?? get_phrase('Updated'));
            $message = get_phrase('Your application status is now :status', ['status' => $status]);
            if (!empty($payload['note'])) {
                $message .= ' · '.\Illuminate\Support\Str::limit(strip_tags($payload['note']), 120);
            }
            $actions = [
                [
                    'label' => get_phrase('View application'),
                    'href' => $notification->action_url ?? ($payload['cta_url'] ?? '#'),
                    'variant' => 'primary',
                    'type' => 'link',
                ],
            ];
            break;
        case 'job_application_status_employer':
            $icon = 'fa-solid fa-user-tie';
            $status = ucfirst($payload['status'] ?? get_phrase('Updated'));
            $message = get_phrase(':candidate moved to :status', [
                'candidate' => $payload['candidate_name'] ?? get_phrase('Candidate'),
                'status' => $status,
            ]);
            $actions = [
                [
                    'label' => get_phrase('Open ATS'),
                    'href' => $notification->action_url ?? ($payload['cta_url'] ?? '#'),
                    'variant' => 'primary',
                    'type' => 'link',
                ],
            ];
            break;
        case 'profile':
            $icon = 'fa-solid fa-user-plus';
            $message = '<strong>'.e($actorName).'</strong> '.get_phrase('sent you a connection request');
            $actions = [
                [
                    'label' => get_phrase('Accept'),
                    'route' => route('accept.friend.request.from.notification', $notification->sender_user_id),
                    'variant' => 'primary',
                ],
                [
                    'label' => get_phrase('Decline'),
                    'route' => route('decline.friend.request.from.notification', $notification->sender_user_id),
                    'variant' => 'ghost',
                ],
            ];
            break;
        case 'friend_request_accept':
        case 'group_invitation_accept':
        case 'event_invitation_accept':
        case 'fundraiser_request_accept':
            $icon = 'fa-solid fa-circle-check';
            $message = '<strong>'.e($actorName).'</strong> '.get_phrase('accepted your request');
            break;
    }
@endphp

<article class="gv-notification-row {{ $state === 'unread' ? 'gv-notification-row--unread' : '' }}"
    data-gv-notification-row
    data-notification-type="{{ $notification->type ?? 'alert' }}"
    data-notification-id="{{ $notification->id }}"
    data-notification-state="{{ $state }}">
    <div class="gv-notification-row__avatar">
        <img src="{{ $avatar }}" alt="{{ $actorName }}" class="gv-notification-row__avatar-img">
        <span class="gv-notification-row__badge">
            <i class="{{ $icon }}"></i>
        </span>
    </div>
    <div class="gv-notification-row__body">
        <div class="gv-notification-row__heading">
            <span class="gv-notification-row__type">{{ $typeLabel }}</span>
            @if($state === 'unread')
                <span class="gv-notification-row__indicator" aria-hidden="true"></span>
            @endif
        </div>
        <p class="gv-notification-row__message">{!! $message !!}</p>
        <div class="gv-notification-row__meta">
            @if($timestamp)
                <span>{{ $timestamp }}</span>
            @endif
            @if($notification->resource_type)
                <span>{{ ucfirst(str_replace('_', ' ', $notification->resource_type)) }}</span>
            @endif
        </div>
        @if(!empty($actions))
            <div class="gv-notification-row__actions">
                @foreach($actions as $action)
                    @if(($action['type'] ?? 'ajax') === 'link')
                        <a href="{{ $action['href'] ?? $action['route'] }}"
                            class="gv-btn gv-btn-sm {{ $action['variant'] === 'primary' ? 'gv-btn-primary' : 'gv-btn-ghost' }}">
                            {{ $action['label'] }}
                        </a>
                    @else
                        <button type="button"
                            class="gv-btn gv-btn-sm {{ $action['variant'] === 'primary' ? 'gv-btn-primary' : 'gv-btn-ghost' }}"
                            onclick="ajaxAction('{{ $action['route'] }}')">
                            {{ $action['label'] }}
                        </button>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
    <div class="gv-notification-row__controls">
        @if($dismissUrl)
            <button type="button" class="gv-btn gv-btn-text" data-gv-notification-dismiss="{{ $dismissUrl }}">
                {{ get_phrase('Mark read') }}
            </button>
        @endif
        @if($notification->action_url)
            <a href="{{ $notification->action_url }}" class="gv-btn gv-btn-icon" aria-label="{{ get_phrase('Open details') }}">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        @endif
    </div>
</article>
