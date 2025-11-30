@php
    $panels = $marketplacePanels ?? [];
    $analytics = $panels['analytics'] ?? [];
    $highlights = $panels['highlights'] ?? [];
@endphp

<div class="gv-shell">
    <div class="gv-shell-header">
        <div class="gv-shell-header-inner">
            <div class="d-flex flex-wrap align-items-center gap-4 w-100">
                <div class="flex-grow-1">
                    <span class="gv-pill-page-label">
                        <span class="gv-pill-page-label-dot"></span>
                        {{ get_phrase('Marketplace') }}
                    </span>
                    <h1 class="gv-main-heading mb-1">{{ get_phrase('Discover and manage listings') }}</h1>
                    <p class="gv-main-heading-sub mb-0">
                        {{ get_phrase('Browse curated products, set alerts, or jump into your seller workspace.') }}
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('userproduct') }}" class="gv-btn gv-btn-ghost">
                        <i class="fa-solid fa-store"></i> {{ get_phrase('My products') }}
                    </a>
                    <a href="{{ route('product.saved') }}" class="gv-btn gv-btn-ghost">
                        <i class="fa-regular fa-bookmark"></i> {{ get_phrase('Saved items') }}
                    </a>
                    <button class="gv-btn gv-btn-primary"
                        onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product']) }}', '{{ get_phrase('Create Product') }}');"
                        data-bs-toggle="modal" data-bs-target="#createProduct">
                        <i class="fa-solid fa-plus"></i> {{ get_phrase('Create listing') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="gv-shell-main">
        <div class="gv-shell-grid">
            <section class="gv-main space-y-4">
                <div class="gv-card gv-marketplace-filter space-y-3">
                    <form method="GET" action="{{ route('filter.product') }}" class="gv-marketplace-filter__form">
                        <label class="gv-input-shell">
                            <span>{{ get_phrase('Search listings') }}</span>
                            <input type="search" class="gv-input submit_on_enter" name="search"
                                value="{{ request('search') }}" placeholder="{{ get_phrase('Type to search products') }}">
                        </label>

                        <div class="gv-marketplace-filter__grid">
                            <label class="gv-input-shell">
                                <span>{{ get_phrase('Condition') }}</span>
                                <select name="condition" class="gv-input" onchange="this.form.submit()">
                                    <option value="">{{ get_phrase('Any condition') }}</option>
                                    <option value="new" @selected(request('condition') === 'new')>{{ get_phrase('New') }}</option>
                                    <option value="used" @selected(request('condition') === 'used')>{{ get_phrase('Used') }}</option>
                                </select>
                            </label>
                            <label class="gv-input-shell">
                                <span>{{ get_phrase('Min price') }}</span>
                                <input type="number" class="gv-input submit_on_enter" name="min" value="{{ request('min') }}"
                                    placeholder="0">
                            </label>
                            <label class="gv-input-shell">
                                <span>{{ get_phrase('Max price') }}</span>
                                <input type="number" class="gv-input submit_on_enter" name="max" value="{{ request('max') }}"
                                    placeholder="9999">
                            </label>
                            <label class="gv-input-shell">
                                <span>{{ get_phrase('Location') }}</span>
                                <input type="text" class="gv-input submit_on_enter" name="location"
                                    value="{{ request('location') }}" placeholder="{{ get_phrase('City or region') }}">
                            </label>
                        </div>
                    </form>
                </div>

                @if (!empty($analytics))
                    <div class="gv-card space-y-3">
                        <p class="gv-eyebrow mb-1">{{ get_phrase('Marketplace signals') }}</p>
                        <div class="row g-3">
                            @foreach ($analytics as $metric)
                                <div class="col-sm-6 col-lg-3">
                                    <div class="gv-card space-y-1 h-100">
                                        <p class="gv-muted mb-0">{{ $metric['label'] ?? '' }}</p>
                                        <h4 class="gv-heading text-2xl mb-0">{{ $metric['value'] ?? '—' }}</h4>
                                        <p class="gv-muted mb-0">{{ $metric['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="gv-card space-y-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="gv-eyebrow mb-1">{{ get_phrase('Listings') }}</p>
                            <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Latest products from the community') }}</h3>
                        </div>
                    </div>
                    @if (count($products) > 0)
                        <div class="row g-3" id="@if(str_contains(url()->current(), '/productdata')) single-item-countable @endif">
                            @include('frontend.marketplace.product-single')
                        </div>
                    @else
                        <div class="gv-empty">
                            <p class="gv-heading text-base mb-1">{{ get_phrase('No listings found') }}</p>
                            <p class="gv-muted mb-0">{{ get_phrase('Try adjusting your filters or check back later.') }}</p>
                        </div>
                    @endif
                </div>
            </section>

            <aside class="gv-sidebar space-y-4">
                @include('components.utilities.quick-tools', ['context' => 'marketplace'])

                @if (!empty($highlights))
                    <div class="gv-card space-y-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="gv-heading text-base mb-0">{{ get_phrase('Highlighted listings') }}</h4>
                            <a href="{{ route('allproducts') }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                                {{ get_phrase('View all') }}
                            </a>
                        </div>
                        <div class="gv-marketplace-highlight-list">
                            @foreach ($highlights as $item)
                                <a href="{{ $item['link'] ?? '#' }}" class="gv-marketplace-highlight d-flex align-items-start gap-3">
                                    <div class="gv-marketplace-highlight__meta">
                                        <p class="gv-heading text-sm mb-0">{{ $item['title'] ?? '' }}</p>
                                        <span class="gv-muted">{{ $item['location'] ?? get_phrase('Global') }}</span>
                                    </div>
                                    <span class="gv-heading text-base">{{ ($item['currency'] ?? '') . ($item['price'] ?? '') }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($marketplaceAd ?? false)
                    @include('advertisement::components.feed_card', ['ad' => $marketplaceAd])
                @endif
            </aside>
        </div>
    </div>
</div>

@section('specific_code_niceselect')
    $('select').niceSelect();
@endsection



