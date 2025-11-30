<div class="gv-card space-y-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Communities & companies') }}</p>
            <h2 class="gv-heading text-lg mb-0">{{ get_phrase('Pages you manage & follow') }}</h2>
        </div>
        <button class="gv-btn gv-btn-primary"
            onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.pages.create_page']) }}', '{{ get_phrase('Create Page') }}');"
            data-bs-toggle="modal" data-bs-target="#createPage">
            <i class="fa-solid fa-plus"></i> {{ get_phrase('Create Page') }}
        </button>
    </div>

    <ul class="gv-profile-tabs">
        <li>
            <button class="gv-nav-link gv-nav-link--active" data-bs-toggle="tab" data-bs-target="#mypage" type="button">
                {{ get_phrase('My Pages') }}
            </button>
        </li>
        <li>
            <button class="gv-nav-link" data-bs-toggle="tab" data-bs-target="#suggest-page" type="button">
                {{ get_phrase('Suggested') }}
            </button>
        </li>
        <li>
            <button class="gv-nav-link" data-bs-toggle="tab" data-bs-target="#linked-page" type="button">
                {{ get_phrase('Liked Pages') }}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pagesTab">
        <div class="tab-pane fade show active" id="mypage">
            @include('frontend.pages.single-page')
        </div>
        <div class="tab-pane fade" id="suggest-page">
            @include('frontend.pages.suggested')
        </div>
        <div class="tab-pane fade" id="linked-page">
            @include('frontend.pages.liked-page')
        </div>
    </div>
</div>

