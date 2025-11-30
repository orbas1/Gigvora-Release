<div class="space-y-4">
    <div class="gv-card">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-4">
            <div>
                <p class="text-uppercase text-muted fw-semibold mb-1">{{ get_phrase('Utilities') }}</p>
                <h1 class="h3 mb-2">{{ get_phrase('Saved items & bookmarks') }}</h1>
                <p class="text-muted mb-0">
                    {{ get_phrase('All of your saved posts, videos, and marketplace items live here, alongside quick jumps into Jobs, Freelance, and Live tools.') }}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-start">
                <a href="{{ route('notifications.index') }}" class="gv-btn gv-btn-ghost">
                    <i class="fa-regular fa-bell me-1"></i> {{ get_phrase('Notifications') }}
                </a>
                <a href="{{ route('calendar.index') }}" class="gv-btn gv-btn-primary">
                    <i class="fa-regular fa-calendar me-1"></i> {{ get_phrase('Schedule & reminders') }}
                </a>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-4 mt-4">
            <div>
                <p class="text-muted text-uppercase small mb-1">{{ get_phrase('Posts & articles') }}</p>
                <h3 class="h2 mb-0">{{ number_format($summary['posts']) }}</h3>
            </div>
            <div>
                <p class="text-muted text-uppercase small mb-1">{{ get_phrase('Videos') }}</p>
                <h3 class="h2 mb-0">{{ number_format($summary['videos']) }}</h3>
            </div>
            <div>
                <p class="text-muted text-uppercase small mb-1">{{ get_phrase('Marketplace') }}</p>
                <h3 class="h2 mb-0">{{ number_format($summary['marketplace']) }}</h3>
            </div>
        </div>
    </div>

    <div class="gv-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">{{ get_phrase('Saved posts') }}</h2>
            <a href="{{ route('profile.savePostList') }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                {{ get_phrase('Manage from profile') }}
            </a>
        </div>
        <div class="d-flex flex-column gap-3">
            @forelse($savedPosts as $post)
                <div class="border rounded-3 p-3 d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0 fw-semibold">{{ optional($post->getUser)->name ?? get_phrase('Gigvora member') }}</p>
                            <small class="text-muted">{{ \Illuminate\Support\Str::upper($post->post_type ?? 'POST') }}</small>
                        </div>
                        <a href="{{ route('single.post', $post->post_id) }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                            {{ get_phrase('Open post') }}
                        </a>
                    </div>
                    <p class="mb-0 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($post->description ?? ''), 200) }}</p>
                </div>
            @empty
                <p class="text-muted mb-0">{{ get_phrase('You have not saved any posts yet.') }}</p>
            @endforelse
        </div>
    </div>

    @if($savedJobs->isNotEmpty())
        <div class="gv-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">{{ get_phrase('Saved jobs') }}</h2>
                @if($integrationLinks['jobsSaved'])
                    <a href="{{ $integrationLinks['jobsSaved'] }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                        {{ get_phrase('Open Jobs hub') }}
                    </a>
                @endif
            </div>
            <div class="d-flex flex-column gap-3">
                @foreach($savedJobs as $bookmark)
                    @php
                        $job = $bookmark->job;
                        $company = $job?->company;
                    @endphp
                    <div class="border rounded-3 p-3 d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $job->title ?? get_phrase('Job role') }}</p>
                                <small class="text-muted">{{ $company->name ?? get_phrase('Company') }}</small>
                            </div>
                            @if($job)
                                <a href="{{ route('jobs.show', $job) }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                                    {{ get_phrase('View') }}
                                </a>
                            @endif
                        </div>
                        <p class="text-muted small mb-0">
                            {{ implode(' · ', array_filter([$job?->location, ucfirst($job?->employment_type ?? '')])) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="gv-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">{{ get_phrase('Saved videos & shorts') }}</h2>
            <a href="{{ route('save.all.view') }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                {{ get_phrase('View video library') }}
            </a>
        </div>
        <div class="d-flex flex-column gap-3">
            @forelse($savedVideos as $bookmark)
                @php
                    $video = $bookmark->getVideo;
                @endphp
                <div class="border rounded-3 p-3 d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-0 fw-semibold">{{ $video->title ?? get_phrase('Untitled video') }}</p>
                        <a href="{{ $video ? route('single.post', $video->post_id) : 'javascript:void(0)' }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                            {{ get_phrase('Watch') }}
                        </a>
                    </div>
                    <small class="text-muted">{{ get_phrase('Category') }}: {{ ucfirst($video->category ?? get_phrase('Video')) }}</small>
                </div>
            @empty
                <p class="text-muted mb-0">{{ get_phrase('No saved videos yet.') }}</p>
            @endforelse
        </div>
    </div>

    <div class="gv-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">{{ get_phrase('Marketplace bookmarks') }}</h2>
            <a href="{{ route('product.saved') }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                {{ get_phrase('Manage marketplace list') }}
            </a>
        </div>
        <div class="d-flex flex-column gap-3">
            @forelse($savedProducts as $bookmark)
                @php
                    $product = $bookmark->productData;
                @endphp
                <div class="border rounded-3 p-3 d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-0 fw-semibold">{{ $product->title ?? get_phrase('Marketplace item') }}</p>
                        <a href="{{ $product ? route('single.product', $product->id) : 'javascript:void(0)' }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                            {{ get_phrase('View listing') }}
                        </a>
                    </div>
                    <small class="text-muted">
                        {{ optional($product)->location ?? get_phrase('Location undisclosed') }} ·
                        {{ optional($product)->price ? get_phrase('Price') . ': ' . $product->price : get_phrase('Price on request') }}
                    </small>
                </div>
            @empty
                <p class="text-muted mb-0">{{ get_phrase('You have not saved any marketplace listings yet.') }}</p>
            @endforelse
        </div>
    </div>

    @if($candidateNotes->isNotEmpty())
        <div class="gv-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">{{ get_phrase('Candidate notes & reminders') }}</h2>
                @if($integrationLinks['jobsSaved'])
                    <a href="{{ $integrationLinks['jobsSaved'] }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                        {{ get_phrase('Open ATS') }}
                    </a>
                @endif
            </div>
            <div class="d-flex flex-column gap-3">
                @foreach($candidateNotes as $application)
                    <div class="border rounded-3 p-3 d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 fw-semibold">{{ optional($application->candidate?->user)->name ?? get_phrase('Candidate') }}</p>
                                <small class="text-muted">{{ $application->job?->title ?? get_phrase('Job application') }}</small>
                            </div>
                            @if(Route::has('employer.applications.show'))
                                <a href="{{ route('employer.applications.show', $application->id) }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                                    {{ get_phrase('Open') }}
                                </a>
                            @endif
                        </div>
                        <p class="mb-0 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($application->notes), 220) }}</p>
                        <small class="text-muted">{{ get_phrase('Updated :date', ['date' => optional($application->updated_at)->format('M d, H:i')]) }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="gv-card">
        <h3 class="h6 mb-3">{{ get_phrase('Cross-addon quick links') }}</h3>
        <div class="d-flex flex-wrap gap-2">
            @if($integrationLinks['jobsSaved'])
                <a href="{{ $integrationLinks['jobsSaved'] }}" class="gv-chip">
                    <i class="fa-solid fa-briefcase me-2"></i> {{ get_phrase('Saved jobs') }}
                </a>
            @endif
            @if($integrationLinks['freelanceDashboard'])
                <a href="{{ $integrationLinks['freelanceDashboard'] }}" class="gv-chip">
                    <i class="fa-solid fa-handshake-angle me-2"></i> {{ get_phrase('Freelance dashboard') }}
                </a>
            @endif
            @if($integrationLinks['liveHub'])
                <a href="{{ $integrationLinks['liveHub'] }}" class="gv-chip">
                    <i class="fa-solid fa-broadcast-tower me-2"></i> {{ get_phrase('Live & events') }}
                </a>
            @endif
            @if($integrationLinks['utilitiesHub'])
                <a href="{{ $integrationLinks['utilitiesHub'] }}" class="gv-chip">
                    <i class="fa-solid fa-bolt me-2"></i> {{ get_phrase('Utilities hub') }}
                </a>
            @endif
        </div>
    </div>
</div>

