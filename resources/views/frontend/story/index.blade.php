@php
    $storyQuickTools = collect([
        [
            'icon' => 'fa-solid fa-plus',
            'label' => get_phrase('Create story'),
            'action' => "createStoryForm('frontend.story.create_story')",
        ],
        [
            'icon' => 'fa-solid fa-poll',
            'label' => get_phrase('Launch poll'),
            'href' => Route::has('utilities.posts.poll') ? route('utilities.posts.poll') : route('utilities.hub'),
        ],
        [
            'icon' => 'fa-solid fa-bell',
            'label' => get_phrase('Add reminder'),
            'href' => Route::has('utilities.hub') ? route('utilities.hub') . '#calendar' : route('utilities.hub'),
        ],
        [
            'icon' => 'fa-solid fa-sparkles',
            'label' => get_phrase('Story templates'),
            'href' => Route::has('utilities.stories.create') ? route('utilities.stories.create') : route('utilities.hub'),
        ],
    ]);
@endphp

<div class="gv-story-card">
    <div class="timeline-carousel owl-carousel owl-loaded owl-drag mb-0 invisible" id="storiesSection">
        <a href="javascript:void(0)"
            onclick="createStoryForm('frontend.story.create_story')"
            class="story-entry m-0 gv-story-entry gv-story-entry--create">
            <div class="gv-story-entry__media" style="background-image: url('{{ get_user_image(Auth()->user()->photo) }}')"></div>
            <div class="gv-story-entry__meta d-flex flex-column align-items-center">
                <span class="gv-story-entry__icon">
                    <i class="fa fa-plus text-white"></i>
                </span>
                <p class="mb-0">{{ get_phrase('Create story') }}</p>
            </div>
        </a>

        @foreach ($stories as $story)
            @php
                $media_preview = DB::table('media_files')
                    ->where('story_id', $story->story_id)
                    ->first();
            @endphp
            <a href="javascript:void(0)"
                class="story-entry creat-story m-0 gv-story-entry"
                onclick="loadStoryDetailsOnModal('{{ $story->story_id }}')">

                @if ($story->content_type == 'text')
                    @php
                        $text_info = json_decode($story->description, true);
                        $text_color = isset($text_info['color']) ? '#' . $text_info['color'] : '#ffffff';
                        $bg_color = isset($text_info['bg-color']) ? '#' . $text_info['bg-color'] : 'var(--gv-color-primary-600)';
                    @endphp
                    <div class="gv-story-entry__media" style="background: {{ $bg_color }}">
                        <div class="gv-story-entry__text" style="color: {{ $text_color }}">
                            {{ $text_info['text'] ?? '' }}
                        </div>
                    </div>
                @elseif ($media_preview)
                    @if ($media_preview->file_type == 'video' && File::exists('public/storage/story/videos/' . $media_preview->file_name))
                        <div class="gv-story-entry__media">
                            <video muted autoplay loop playsinline>
                                <source src="{{ asset('storage/story/videos/' . $media_preview->file_name) }}" type="video/mp4">
                            </video>
                        </div>
                    @else
                        <div class="gv-story-entry__media"
                            style="background-image: url('{{ asset('storage/story/images/' . $media_preview->file_name) }}')">
                        </div>
                    @endif
                @else
                    <div class="gv-story-entry__media"
                        style="background-image: url('{{ get_user_image($story->photo, 'optimized') }}')">
                    </div>
                @endif

                <div class="gv-story-entry__meta">
                    <div class="gv-story-entry__avatar">
                        <img src="{{ get_user_image($story->photo, 'optimized') }}" alt="{{ $story->name }}">
                    </div>
                    <h4 class="text-nav">{{ $story->name }}</h4>
                    <p class="text-des">{{ date_formatter($story->created_at, 2) }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <div class="gv-story-quick-tools">
        @foreach ($storyQuickTools as $tool)
            @if (!empty($tool['href']))
                <a href="{{ $tool['href'] }}" class="gv-story-quick-pill">
                    <i class="{{ $tool['icon'] }}"></i>
                    <span>{{ $tool['label'] }}</span>
                </a>
            @else
                <button type="button" class="gv-story-quick-pill" onclick="{!! $tool['action'] !!}">
                    <i class="{{ $tool['icon'] }}"></i>
                    <span>{{ $tool['label'] }}</span>
                </button>
            @endif
        @endforeach
    </div>
</div>

@include('frontend.story.scripts')