@php
    $panels = $marketplacePanels ?? [];
    $analytics = $panels['analytics'] ?? [];
@endphp

<div class="gv-shell">
    <div class="gv-shell-header">
        <div class="gv-shell-header-inner">
            <div class="d-flex flex-wrap gap-4 w-100">
                <div class="flex-grow-1">
                    <span class="gv-pill-page-label">
                        <span class="gv-pill-page-label-dot"></span>
                        {{ get_phrase('Marketplace manager') }}
                    </span>
                    <h1 class="gv-main-heading mb-1">{{ get_phrase('Your listings & performance') }}</h1>
                    <p class="gv-main-heading-sub mb-0">
                        {{ get_phrase('Track products, edit listings, and keep Utilities quick tools close by.') }}
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('allproducts') }}" class="gv-btn gv-btn-ghost">
                        <i class="fa-solid fa-magnifying-glass"></i> {{ get_phrase('Browse marketplace') }}
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
                <div class="gv-card space-y-3">
                    <p class="gv-eyebrow mb-1">{{ get_phrase('Active listings') }}</p>
                    <div class="space-y-3">
                        @forelse ($products as $product)
                            <article class="gv-marketplace-card gv-marketplace-card--manager" id="product-{{ $product->id }}">
                                <div class="d-flex gap-3 align-items-start">
                                    <a href="{{ route('single.product', $product->id) }}"
                                        class="gv-marketplace-card__media"
                                        style="background-image: url('{{ get_product_image($product->image, 'thumbnail') }}')"
                                        aria-label="{{ $product->title }}">
                                    </a>
                                    <div class="flex-grow-1 space-y-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('single.product', $product->id) }}"
                                                class="gv-heading text-base mb-0">
                                                {{ ellipsis($product->title, 48) }}
                                            </a>
                                            <span class="gv-marketplace-card__price">{{ $product->getCurrency->symbol }}{{ $product->price }}</span>
                                        </div>
                                        <p class="gv-muted mb-0">
                                            <i class="fa-solid fa-location-dot me-1"></i>{{ $product->location ?? get_phrase('Global') }}
                                            · {{ get_phrase('Status') }}: {{ $product->status ? get_phrase('Live') : get_phrase('Draft') }}
                                        </p>
                                        <div class="gv-marketplace-card__actions">
                                            <a href="{{ route('single.product', $product->id) }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                                                {{ get_phrase('View') }}
                                            </a>
                                            <button class="gv-btn gv-btn-ghost gv-btn-sm"
                                                onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.marketplace.edit_product', 'product_id' => $product->id]) }}', '{{ get_phrase('Edit Product') }}');"
                                                data-bs-toggle="modal" data-bs-target="#editProduct">
                                                <i class="fa-solid fa-pen"></i> {{ get_phrase('Edit') }}
                                            </button>
                                            <button class="gv-btn gv-btn-ghost gv-btn-sm text-danger"
                                                onclick="confirmAction('{{ route('product.delete', ['product_id' => $product->id]) }}', true)">
                                                <i class="fa-solid fa-trash-can"></i> {{ get_phrase('Delete') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="gv-empty">
                                <p class="gv-heading text-base mb-1">{{ get_phrase('No products yet') }}</p>
                                <p class="gv-muted mb-0">{{ get_phrase('Create your first listing to showcase it here.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <aside class="gv-sidebar space-y-4">
                @include('components.utilities.quick-tools', ['context' => 'marketplace_manager'])

                @if (!empty($analytics))
                    <div class="gv-card space-y-3">
                        <h4 class="gv-heading text-base mb-0">{{ get_phrase('Marketplace analytics') }}</h4>
                        <div class="space-y-2">
                            @foreach ($analytics as $metric)
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="gv-muted">{{ $metric['label'] ?? '' }}</span>
                                    <strong>{{ $metric['value'] ?? '—' }}</strong>
                                </div>
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