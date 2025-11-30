@php
    $photoItems = $all_photos->where('file_type', 'image');
    $reelItems = $all_photos->where('file_type', 'video');
@endphp

<div class="gv-card album_tab space-y-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Memories') }}</p>
            <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Photos & Reels') }}</h3>
        </div>
        <a onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.groups.album_image','profile_id'=>$user_info->id]) }}', '{{ get_phrase('Add Photo To Album') }}');"
            data-bs-toggle="modal"
            data-bs-target="#albumCreateModal"
            class="gv-btn gv-btn-primary">
            {{ get_phrase('Add Photo/Album') }}
        </a>
    </div>

    <div class="gv-media-grid" id="allPhotos">
        @forelse ($photoItems as $photo)
            <a class="gv-media-grid__item" style="background-image: url('{{ get_post_image($photo->file_name) }}')" href="{{ route('single.post', $photo->post_id) }}">
                <span class="sr-only">{{ get_phrase('Open photo') }}</span>
            </a>
        @empty
            <p class="gv-muted mb-0">{{ get_phrase('No photos yet.') }}</p>
        @endforelse
    </div>

    <div class="space-y-3">
        <h4 class="gv-heading text-base mb-0">{{ get_phrase('Reels spotlight') }}</h4>
        <div class="gv-media-rail" id="reelRail">
            @forelse ($reelItems as $reel)
                <div class="gv-media-card gv-media-card--vertical">
                    <div class="gv-media-card__media">
                        <video muted loop playsinline src="{{ get_post_video($reel->file_name) }}"></video>
                        <button class="gv-media-card__cta" onclick="$(location).prop('href', '{{ route('single.post', $reel->post_id) }}')">
                            <i class="fa-solid fa-play"></i>
                        </button>
                    </div>
                    <div class="gv-media-card__meta">
                        <p class="text-xs gv-muted mb-1">{{ get_phrase('Reel') }}</p>
                        <span class="text-sm">{{ date_formatter($reel->created_at, 2) }}</span>
                    </div>
                </div>
            @empty
                <p class="gv-muted mb-0">{{ get_phrase('No reels yet.') }}</p>
            @endforelse
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2" role="tablist">
        @php
            $photoTabs = [
                ['id' => 'profile-photo', 'label' => get_phrase('Your Photos')],
                ['id' => 'profile-album', 'label' => get_phrase('Album')],
            ];
        @endphp
        @foreach ($photoTabs as $index => $tab)
            <button class="gv-pill tab-trigger @if ($index === 0) active @endif"
                id="{{ $tab['id'] }}-tab"
                data-bs-toggle="tab"
                data-bs-target="#{{ $tab['id'] }}"
                type="button"
                role="tab"
                aria-controls="{{ $tab['id'] }}"
                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="profile-photo" role="tabpanel"
            aria-labelledby="profile-photo-tab">
            <div class="photo-list mt-3">
                <div class="photoGallery flex-wrap" id="allPhotos">
                    @include('frontend.profile.photo_single')
                </div>
            </div>
        </div> <!-- Tab Pane End -->
        <div class="tab-pane fade" id="profile-album" role="tabpanel" aria-labelledby="profile-tab">
            <div class="friends-request my-3 g-2">
                <div class="row" id="profile-album-row">
                    <div class="grid_control">
                        <div class="col-create-album">
                            <div class="card album-create-card new_album min-auto">
                                <a onclick="showCustomModal('<?php echo route('profile.album', ['action_type' => 'form']); ?>', '{{get_phrase('Create Album')}}')" class="create-album">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                                <h4 class="h6">{{get_phrase('Create Album')}}</h4>
                            </div>
                        </div> <!-- Card End -->
                        @include('frontend.profile.album_single')
                    </div>
                    
                </div>
            </div>
        </div><!-- Tab Pane End -->

    </div> <!-- Tab Content End -->
</div>
