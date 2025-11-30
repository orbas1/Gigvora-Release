@php
    $filters = config('media_studio.filters', []);
    $musicTracks = config('media_studio.music', []);
    $stickers = config('media_studio.stickers', []);
    $gifs = config('media_studio.gifs', []);
@endphp

<div class="gv-composer-studio" id="gvComposerStudio" data-active-mode="standard"
    data-studio-stickers='@json(array_keys($stickers))'
    data-studio-gifs='@json(array_keys($gifs))'
    data-music-tracks='@json(array_keys($musicTracks))'>
    <div class="gv-composer-studio__tabs">
        <button type="button" class="gv-composer-studio__tab is-active" data-mode="standard">
            <i class="fa-regular fa-square-pen"></i>
            <span>{{ get_phrase('Classic') }}</span>
        </button>
        <button type="button" class="gv-composer-studio__tab" data-mode="story">
            <i class="fa-solid fa-bolt"></i>
            <span>{{ get_phrase('Story') }}</span>
        </button>
        <button type="button" class="gv-composer-studio__tab" data-mode="reel">
            <i class="fa-solid fa-film"></i>
            <span>{{ get_phrase('Reel / Short') }}</span>
        </button>
        <button type="button" class="gv-composer-studio__tab" data-mode="longform">
            <i class="fa-solid fa-clapperboard"></i>
            <span>{{ get_phrase('Longform') }}</span>
        </button>
        <button type="button" class="gv-composer-studio__tab" data-mode="live">
            <i class="fa-solid fa-podcast"></i>
            <span>{{ get_phrase('Live') }}</span>
        </button>
    </div>

    <div class="gv-composer-studio__panel" data-panel="story">
        <div class="gv-composer-panel-grid">
            <div>
                <label class="gv-label" for="storyFilter">{{ get_phrase('Filter') }}</label>
                <select class="gv-input" id="storyFilter" data-studio-filter="story">
                    @foreach ($filters as $key => $filter)
                        <option value="{{ $key }}">{{ get_phrase($filter['label']) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="gv-label">{{ get_phrase('Aspect ratio') }}</label>
                <div class="gv-composer-pill-group" data-studio-aspect="story">
                    <button type="button" data-value="9:16" class="is-active">9:16</button>
                    <button type="button" data-value="1:1">1:1</button>
                    <button type="button" data-value="16:9">16:9</button>
                </div>
            </div>
        </div>

        <div class="gv-composer-panel-grid">
            <div>
                <label class="gv-label">{{ get_phrase('Overlays & stickers') }}</label>
                <div class="gv-composer-overlay-actions" data-overlay-target="story">
                    <button type="button" data-action="text"><i class="fa-regular fa-font-case"></i> {{ get_phrase('Add text') }}</button>
                    <button type="button" data-action="emoji"><i class="fa-regular fa-face-smile"></i> {{ get_phrase('Emoji') }}</button>
                    <button type="button" data-action="sticker"><i class="fa-solid fa-star"></i> {{ get_phrase('Sticker') }}</button>
                    <button type="button" data-action="gif"><i class="fa-solid fa-gifts"></i> {{ get_phrase('GIF') }}</button>
                </div>
                <div class="gv-composer-overlay-list" data-overlay-list="story"></div>
            </div>
            <div>
                <label class="gv-label" for="storyMusic">{{ get_phrase('Soundtrack') }}</label>
                <select class="gv-input" id="storyMusic" data-studio-music="story">
                    <option value="">{{ get_phrase('None') }}</option>
                    @foreach ($musicTracks as $key => $track)
                        <option value="{{ $key }}">{{ $track['label'] }}</option>
                    @endforeach
                </select>
                <p class="gv-muted mt-1 text-sm">{{ get_phrase('Royalty-free Gigvora cuts for quick stories.') }}</p>
            </div>
        </div>
    </div>

    <div class="gv-composer-studio__panel" data-panel="reel">
        <div class="gv-composer-panel-grid">
            <div>
                <label class="gv-label" for="reelFilter">{{ get_phrase('Filter') }}</label>
                <select class="gv-input" id="reelFilter" data-studio-filter="reel">
                    @foreach ($filters as $key => $filter)
                        <option value="{{ $key }}">{{ get_phrase($filter['label']) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="gv-label">{{ get_phrase('Resolution preference') }}</label>
                <div class="gv-composer-pill-group" data-resolution-selector="reel">
                    <button type="button" data-value="4k">4K</button>
                    <button type="button" data-value="1080p" class="is-active">1080p</button>
                    <button type="button" data-value="780p">780p</button>
                    <button type="button" data-value="480p">480p</button>
                </div>
            </div>
        </div>
        <div>
            <label class="gv-label">{{ get_phrase('Tags & location') }}</label>
            <input type="text" class="gv-input mb-2" id="reelTagsInput" placeholder="{{ get_phrase('Add #tags separated by comma') }}">
            <input type="text" class="gv-input" id="reelLocationInput" placeholder="{{ get_phrase('Location (optional)') }}">
        </div>
    </div>

    <div class="gv-composer-studio__panel" data-panel="longform">
        <div class="gv-composer-panel-grid">
            <div>
                <label class="gv-label" for="longformDate">{{ get_phrase('Schedule date') }}</label>
                <input type="date" class="gv-input" id="longformDate" name="scheduled_date">
            </div>
            <div>
                <label class="gv-label" for="longformTime">{{ get_phrase('Schedule time') }}</label>
                <input type="time" class="gv-input" id="longformTime" name="scheduled_time">
            </div>
        </div>
        <div>
            <label class="gv-label">{{ get_phrase('Premiere options') }}</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="longformNotify" data-longform-premiere="notify">
                <label class="form-check-label" for="longformNotify">
                    {{ get_phrase('Notify followers before premiere') }}
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="longformQna" data-longform-premiere="qna">
                <label class="form-check-label" for="longformQna">
                    {{ get_phrase('Enable live Q&A thread') }}
                </label>
            </div>
        </div>
    </div>

    <div class="gv-composer-studio__panel" data-panel="live">
        <div class="gv-composer-panel-grid">
            <div>
                <label class="gv-label" for="liveDonationGoal">{{ get_phrase('Donation goal (£)') }}</label>
                <input type="number" min="0" step="1" name="live_donation_goal" id="liveDonationGoal" class="gv-input" placeholder="500">
            </div>
            <div>
                <label class="gv-label" for="liveViewerGoal">{{ get_phrase('Viewer goal') }}</label>
                <input type="number" min="0" step="1" name="live_viewer_goal" id="liveViewerGoal" class="gv-input" placeholder="100">
            </div>
        </div>
        <div>
            <label class="gv-label">{{ get_phrase('CTA links (projects, marketplace, podcasts)') }}</label>
            <div class="gv-composer-cta-list" data-live-cta-list>
                <div class="gv-composer-cta-row">
                    <input type="text" name="live_cta_links[0][label]" class="gv-input" placeholder="{{ get_phrase('Label e.g. Projects') }}">
                    <input type="url" name="live_cta_links[0][url]" class="gv-input" placeholder="https://">
                </div>
                <div class="gv-composer-cta-row">
                    <input type="text" name="live_cta_links[1][label]" class="gv-input" placeholder="{{ get_phrase('Label e.g. Marketplace') }}">
                    <input type="url" name="live_cta_links[1][url]" class="gv-input" placeholder="https://">
                </div>
            </div>
            <button type="button" class="gv-btn gv-btn-ghost mt-2" data-add-cta data-label="{{ get_phrase('Label') }}">{{ get_phrase('Add another link') }}</button>
        </div>
    </div>
</div>

