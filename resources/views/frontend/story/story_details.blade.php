@php
    $storyUtilities = collect([
        [
            'icon' => 'fa-solid fa-poll',
            'label' => get_phrase('Poll'),
            'href' => Route::has('utilities.posts.poll') ? route('utilities.posts.poll') : route('utilities.hub'),
        ],
        [
            'icon' => 'fa-solid fa-bell',
            'label' => get_phrase('Reminder'),
            'href' => Route::has('utilities.hub') ? route('utilities.hub') . '#calendar' : route('utilities.hub'),
        ],
        [
            'icon' => 'fa-solid fa-link',
            'label' => get_phrase('CTA thread'),
            'href' => Route::has('utilities.posts.thread') ? route('utilities.posts.thread') : route('utilities.hub'),
        ],
    ]);
@endphp

<div class="timeline-carousel px-3 bg-white owl-carousel">
    <!--  avatar end -->
    <a href="#" class="story-entry story-entry-details active" onclick="loadSingleStoryDetailsOnModal('{{$story_details->story_id}}', this)">
        <div class="avatar-online d-flex align-items-center mb-2">
            <div class="avatar-img"> <img src="{{get_user_image($story_details->photo, 'optimized')}}" alt="">
            </div>
            <div class="avatar-info ms-2">
                <h4 class="ava-nave">{{$story_details->name}}</h4>
                <div class="activity-time small-text text-muted">{{date_formatter($story_details->created_at, 2)}}</div>
            </div>
        </div>
    </a><!--  avatar end -->

    @foreach ($stories as $story)
        <!--  avatar end -->
        <a href="#" class="story-entry story-entry-details" onclick="loadSingleStoryDetailsOnModal('{{$story->story_id}}', this)">
            <div class="avatar-online d-flex align-items-center mb-2">
                <div class="avatar-img"> <img src="{{get_user_image($story->photo, 'optimized')}}" alt="">
                </div>
                <div class="avatar-info ms-2">
                    <h4 class="ava-nave">{{$story->name}}</h4>
                    <div class="activity-time small-text text-muted">{{date_formatter($story->created_at, 2)}}</div>
                </div>
            </div>
        </a><!--  avatar end -->
    @endforeach
</div> <!-- Online Status End -->


<div class="stg-wrap" id="stg-wrap-story-gallery">
    <div class="story-gallery owl-carousel">
        <div class="st-item">
            <div class="carousel-inner mb-5">
                <div class="stc-wrap">
                    <div class="st-child-gallery stc-bg owl-carousel" onclick="player.togglePlay(toggle)">
                        <div class="gv-story-toolbar">
                            @foreach ($storyUtilities as $utility)
                                <a href="{{ $utility['href'] }}" class="gv-story-toolbar__btn">
                                    <i class="{{ $utility['icon'] }}"></i>
                                    <span>{{ $utility['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                        @php($storyAd = config('advertisement.enabled') ? app(\App\Services\AdvertisementSurfaceService::class)->forSlot('story_interstitial') : null)
                        <div class="gv-story-ad">
                            @includeWhen($storyAd, 'advertisement::components.ad_feed_card', ['ad' => $storyAd])
                        </div>
                        @if($story_details->content_type == 'text')
                            @php
                                $text_info = json_decode($story_details->description, true);
                            @endphp
                            <div class="stories-view mt-3 py-4" style="color: <?php echo '#'.$text_info['color']; ?>; background-color: <?php echo '#'.$text_info['bg-color']; ?>;">
                                {{$text_info['text']}}
                            </div>  
                        @else
                            @php
                                $media_files = DB::table('media_files')
                                    ->where('story_id', $story_details->story_id)
                                    ->get()
                                    ->map(function ($file) {
                                        $file->processing_manifest = $file->processing_manifest ? json_decode($file->processing_manifest, true) : [];
                                        return $file;
                                    });
                            @endphp
                            @foreach($media_files as $media_file)
                                @php
                                    $manifest = $media_file->processing_manifest ?? [];
                                    $filterKey = $manifest['filter'] ?? 'none';
                                    $filterCss = $filterKey !== 'none' ? (config('media_studio.filters.' . $filterKey . '.css') ?? null) : null;
                                @endphp
                                @if($media_file->file_type == 'video')
                                    @if(File::exists('public/storage/story/videos/'.$media_file->file_name))
                                        <div class="gv-media-stage">
                                            <video class="plyr-js" width="100%" autoplay controlsList="nodownload" @if ($filterCss) style="filter: {{ $filterCss }};" @endif>
                                                <source src="{{asset('storage/story/videos/'.$media_file->file_name)}}" type="">
                                            </video>
                                            @include('frontend.main_content.media_overlays', ['manifest' => $manifest])
                                        </div>
                                    @endif
                                @else
                                    <div class="gv-media-stage">
                                        <img class="w-100" src="{{asset('storage/story/images/'.$media_file->file_name)}}" @if ($filterCss) style="filter: {{ $filterCss }};" @endif>
                                        @include('frontend.main_content.media_overlays', ['manifest' => $manifest])
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Owl Carousel End -->
</div>