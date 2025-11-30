@extends('freelance::layouts.freelance')

@section('freelance-content')
<div class="gv-main">
    <div class="gv-card gv-profile-card mb-4">
        <div class="d-flex align-items-center gap-4">
            <div class="gv-profile-avatar-lg">
                <div class="gv-profile-avatar-inner">
                    <img src="{{ get_user_image($profile->user_id, 'optimized') }}" alt="{{ $profile->full_name }}" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
            <div class="gv-profile-meta">
                <h1 class="gv-profile-name mb-1">{{ $profile->full_name }}</h1>
                <p class="gv-profile-headline mb-2">{{ $profile->tagline ?? __('Seasoned freelancer') }}</p>
                <div class="gv-profile-tags">
                    @foreach(($profile->skills->pluck('name')->take(4) ?? []) as $skill)
                        <span class="gv-pill">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
            <div class="ms-auto">
                <form method="POST" action="{{ route('freelance.messages.send') }}" class="d-flex gap-2">
                    @csrf
                    <input type="hidden" name="recipient_id" value="{{ $profile->user_id }}">
                    <input type="hidden" name="recipient_slug" value="{{ $profile->slug }}">
                    <button class="gv-btn gv-btn-primary" type="submit">{{ __('Message') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="gv-card mb-4">
                <h2 class="h5 mb-3">{{ __('About') }}</h2>
                <p class="mb-0 gv-muted">{{ $profile->description ?? __('This freelancer has not added a full biography yet.') }}</p>
            </div>

            <div class="gv-card">
                <h2 class="h5 mb-3">{{ __('Featured gigs') }}</h2>
                <div class="d-flex flex-column gap-3">
                    @forelse($profile->gigs->take(4) as $gig)
                        <div>
                            <h3 class="h6 mb-1">{{ $gig->title }}</h3>
                            <p class="mb-0 gv-muted">{{ ellipsis($gig->description, 120) }}</p>
                        </div>
                    @empty
                        <p class="mb-0 gv-muted">{{ __('No gigs published yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="gv-card mb-4">
                <h2 class="h6 mb-3 text-uppercase text-muted">{{ __('Availability') }}</h2>
                <p class="mb-2">{{ __('Location') }}: <strong>{{ $profile->country ?? __('Remote') }}</strong></p>
                <p class="mb-0">{{ __('Member since') }}: <strong>{{ optional($profile->created_at)->format('M Y') }}</strong></p>
            </div>
            <div class="gv-card">
                <h2 class="h6 mb-3 text-uppercase text-muted">{{ __('Portfolio') }}</h2>
                @forelse($profile->portfolio->take(3) as $item)
                    <p class="mb-1">{{ $item->title }}</p>
                @empty
                    <p class="mb-0 gv-muted">{{ __('No portfolio items shared yet.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

