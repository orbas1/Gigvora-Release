<div class="row" id="postMediaSection{{ $post->post_id }}">
    <div class="col-12">
        @php
            $media_files = DB::table('media_files')
                ->where('post_id', $post->post_id)
                ->get()
                ->map(function ($file) {
                    $file->processing_manifest = $file->processing_manifest ? json_decode($file->processing_manifest, true) : [];
                    return $file;
                });
        @endphp
        @php $media_files_count = count($media_files); @endphp
        @php($videoSwipeAd = config('advertisement.enabled') ? app(\App\Services\AdvertisementSurfaceService::class)->forSlot('video_swipe') : null)
        <div class="mb-3">
            @includeWhen($videoSwipeAd, 'advertisement::components.ad_feed_card', ['ad' => $videoSwipeAd])
        </div>
        <div class="photoGallery  visibility-hidden @if ($media_files_count == 1) initialized mt-12 @endif">
            <!-- break after loaded 5 images -->
            @php $more_unloaded_images = $media_files_count - 5; @endphp
            @foreach ($media_files as $key => $media_file)

                @php
                    if ($key == 5) {
                        break;
                    }
                @endphp

                @php
                    $manifest = $media_file->processing_manifest ?? [];
                    $filterKey = $manifest['filter'] ?? 'none';
                    $filterCss = $filterKey !== 'none' ? (config('media_studio.filters.' . $filterKey . '.css') ?? null) : null;
                @endphp

                @if ($media_file->file_type == 'video')
                @php $s3_keys = get_settings('amazon_s3', 'object'); @endphp
                    @if (File::exists('public/storage/post/videos/' . $media_file->file_name) || $s3_keys->active == 1)
                        @if ($media_files_count > 1)
                            <a class="position-relative"
                                onclick="showCustomModal('{{ route('preview_post', ['post_id' => $post->post_id, 'file_name' => $media_file->file_name]) }}', '{{ get_phrase('Preview') }}', 'xxl')"
                                href="javascript:void(0)">
                        @endif

                        <div class="gv-media-stage">
                            <video muted controlsList="nodownload"
                                class="plyr-js w-100 rounded video-thumb @if ($media_files_count > 1) initialized @endif"
                                onplay="pauseOtherVideos(this)" @if ($filterCss) style="filter: {{ $filterCss }};" @endif>
                                <source src="{{ get_post_video($media_file->file_name) }}" type="">
                            </video>
                            @include('frontend.main_content.media_overlays', ['manifest' => $manifest])
                        </div>

                        @if ($more_unloaded_images > 0 && $key == 4)
                            <div class="more_image_overlap"><span><i class="fa-solid fa-plus"></i>
                                    {{ $more_unloaded_images }}</span></div>
                        @endif

                        @if ($media_files_count > 1)
                            </a>
                        @endif
                    @endif
                @else
                    <div class="picture text-center">
                        <a onclick="showCustomModal('{{ route('preview_post', ['post_id' => $post->post_id, 'file_name' => $media_file->file_name]) }}', '{{ get_phrase('Preview') }}', 'xxl')"
                            href="javascript:void(0)">

                            @if ($more_unloaded_images > 0 && $key == 4)
                                @php $opacity = 'opacity-7'; @endphp
                                <div class="more_image_overlap"><span><i class="fa-solid fa-plus"></i>
                                        {{ $more_unloaded_images }}</span></div>
                            @else
                                @php $opacity = ''; @endphp
                            @endif
                            @if(!isset($post_albums) )            
                            <div class="gv-media-stage">
                                <img src="{{ get_post_image($media_file->file_name) }}"
                                    class="w-100 h-100 @if ($media_files_count == 1) single-image-ration @endif {{ $opacity }}"
                                    alt="" @if ($filterCss) style="filter: {{ $filterCss }};" @endif>
                                @include('frontend.main_content.media_overlays', ['manifest' => $manifest])
                            </div>
                             @endif   
                        </a>
                    </div>
                @endif

            @endforeach
        </div>
    </div>
</div>
