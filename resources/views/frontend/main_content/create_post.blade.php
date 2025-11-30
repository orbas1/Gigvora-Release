@php
    $jobsEnabled = config('jobs.features.enabled');
    $jobsEmployerRoles = (array) config('jobs.roles.employer_access', []);
    $userRole = auth()->user()->user_role ?? null;
    $canAuthorJobs = $jobsEnabled && $userRole && in_array($userRole, $jobsEmployerRoles, true);
    $jobsBrowseRoute = Route::has('jobs.index') ? route('jobs.index') : url('/jobs');

    $composerActions = collect([
        [
            'key' => 'post',
            'label' => get_phrase('Quick update'),
            'description' => get_phrase('Share text, polls or announcements'),
            'icon' => 'fa-regular fa-pen-to-square',
            'action' => 'modal',
            'class' => '',
        ],
        [
            'key' => 'media',
            'label' => get_phrase('Media & reels'),
            'description' => get_phrase('Upload photos, reels or long-form video'),
            'icon' => 'fa-solid fa-photo-film',
            'action' => 'modal',
            'class' => '',
        ],
        [
            'key' => 'job',
            'label' => get_phrase('Jobs'),
            'description' => $canAuthorJobs
                ? get_phrase('Post or manage openings')
                : get_phrase('Browse openings and track applications'),
            'icon' => 'fa-solid fa-briefcase',
            'action' => 'link',
            'href' => $canAuthorJobs && Route::has('create.job') ? route('create.job') : $jobsBrowseRoute,
            'class' => 'gv-composer-pill--job',
            'enabled' => $jobsEnabled,
        ],
        [
            'key' => 'freelance',
            'label' => get_phrase('Freelance'),
            'description' => get_phrase('Launch gigs or projects'),
            'icon' => 'fa-solid fa-handshake-angle',
            'action' => 'link',
            'href' => Route::has('freelance.buyer.projects.create') ? route('freelance.buyer.projects.create') : url('/freelance/dashboard'),
            'class' => 'gv-composer-pill--freelance',
            'enabled' => addon_status('freelance') == 1,
        ],
        [
            'key' => 'live',
            'label' => get_phrase('Live & events'),
            'description' => get_phrase('Plan webinars, live pods or interviews'),
            'icon' => 'fa-solid fa-podcast',
            'action' => 'link',
            'href' => Route::has('liveCenter.hub') ? route('liveCenter.hub') : route('event'),
            'class' => 'gv-composer-pill--live',
            'enabled' => Route::has('liveCenter.hub'),
        ],
        [
            'key' => 'utilities',
            'label' => get_phrase('Utilities'),
            'description' => get_phrase('Launch reminders, bookmarks & quick tools'),
            'icon' => 'fa-solid fa-bolt',
            'action' => 'link',
            'href' => Route::has('utilities.hub') ? route('utilities.hub') : url('/utilities/hub'),
            'class' => 'gv-composer-pill--utilities',
        ],
    ])
        ->filter(fn($action) => $action['enabled'] ?? true)
        ->values();
@endphp

<div class="gv-card gv-feed-card newsfeed-form">
    <div class="entry-inner space-y-4">
        <div class="gv-feed-composer">
            @if (isset($page_id) && !empty($page_id))
                @php
                    $page = \App\Models\Page::find($page_id);
                @endphp
                <a href="{{ route('single.page', $page_id) }}" class="author-thumb d-flex align-items-center">
                    <img src="{{ get_page_logo($page->logo, 'logo') }}" width="48" height="48" class="rounded-circle"
                        alt="">
                </a>
            @else
                <a href="{{ route('profile') }}" class="author-thumb d-flex align-items-center">
                    <img src="{{ get_user_image($user_info->photo, 'optimized') }}" width="48" height="48"
                        class="rounded-circle" alt="">
                </a>
            @endif

            <button class="flex-1 text-left text-[var(--gv-color-neutral-500)] font-medium bg-[var(--gv-color-neutral-100)] hover:bg-[var(--gv-color-neutral-200)] rounded-lg py-3 px-4 transition"
                data-bs-toggle="modal" data-bs-target="#createPost" onclick="$('#createPost').modal('show');">
                {{ get_phrase("What's on your mind ____", [auth()->user()->name]) }}?
            </button>

            @if (isset($page_id) && !empty($page_id))
                @include('frontend.main_content.create_post_modal', ['page_id' => $page_id])
            @elseif (isset($group_id) && !empty($group_id))
                @include('frontend.main_content.create_post_modal', ['group_id' => $group_id])
            @elseif (isset($paid_content_id) && !empty($paid_content_id))
                @include('frontend.main_content.create_post_modal', [
                    'paid_content_id' => $paid_content_id,
                ])
            @else
                @include('frontend.main_content.create_post_modal')
            @endif
        </div>

        @if ($composerActions->isNotEmpty())
            <div class="gv-composer-modes">
                @foreach ($composerActions as $action)
                    @if ($action['action'] === 'modal')
                        <button type="button"
                            class="gv-composer-pill {{ $action['class'] ?? '' }}"
                            data-bs-toggle="modal" data-bs-target="#createPost" onclick="$('#createPost').modal('show');">
                            <strong><i class="{{ $action['icon'] }}"></i>{{ $action['label'] }}</strong>
                            <span>{{ $action['description'] }}</span>
                        </button>
                    @else
                        <a href="{{ $action['href'] }}" class="gv-composer-pill {{ $action['class'] ?? '' }}">
                            <strong><i class="{{ $action['icon'] }}"></i>{{ $action['label'] }}</strong>
                            <span>{{ $action['description'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
