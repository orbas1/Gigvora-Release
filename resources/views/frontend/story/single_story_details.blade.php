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

<div class="stg-wrap" id="stg-wrap-story-gallery">
    <div class="story-gallery owl-carousel">
        <div class="st-item">
            <div class="carousel-inner mb-5">
                <div class="stc-wrap">
                    <div class="st-child-gallery stc-bg owl-carousel">
                        <div class="gv-story-toolbar">
                            @foreach ($storyUtilities as $utility)
                                <a href="{{ $utility['href'] }}" class="gv-story-toolbar__btn">
                                    <i class="{{ $utility['icon'] }}"></i>
                                    <span>{{ $utility['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                        
                        @if($story_details->content_type == 'text')
                            @php
                                $text_info = json_decode($story_details->description, true);
                            @endphp
                            <div class="stories-view mt-3 py-4" style="color: <?php echo '#'.$text_info['color']; ?>; background-color: <?php echo '#'.$text_info['bg-color']; ?>;">
                                {{$text_info['text']}}
                            </div>  
                        @else
                            @php $media_files = DB::table('media_files')->where('story_id', $story_details->story_id)->get(); @endphp
                            @foreach($media_files as $media_file)
                                @if($media_file->file_type == 'video')
                                    @if(File::exists('public/storage/story/videos/'.$media_file->file_name))
                                        <video class="plyr-js" width="100%" autoplay controlsList="nodownload">
                                            <source src="{{asset('storage/story/videos/'.$media_file->file_name)}}" type="">
                                        </video>
                                    @endif
                                @else
                                    <img class="w-100" src="{{asset('storage/story/images/'.$media_file->file_name)}}">
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div> <!-- Owl Carousel End -->
</div>