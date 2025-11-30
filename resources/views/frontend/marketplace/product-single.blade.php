@foreach ($products as $key => $product)
    <div class="col-12 col-sm-6 col-md-4 col-xl-3 mb-3 @if(str_contains(url()->current(), '/products')) single-item-countable @endif">
        <article class="gv-marketplace-card">
            <a href="{{ route('single.product', $product->id) }}"
                class="gv-marketplace-card__media"
                style="background-image: url('{{ get_product_image($product->image, 'thumbnail') }}')"
                aria-label="{{ $product->title }}">
            </a>
            <div class="gv-marketplace-card__body">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="gv-marketplace-card__price">{{ $product->getCurrency->symbol }}{{ $product->price }}</span>
                    @if ($product->condition)
                        <span class="gv-marketplace-card__condition">{{ ucfirst($product->condition) }}</span>
                    @endif
                </div>
                <a href="{{ route('single.product', $product->id) }}" class="gv-marketplace-card__title">
                    {{ ellipsis($product->title, 42) }}
                </a>
                <p class="gv-marketplace-card__meta">
                    <i class="fa-solid fa-location-dot me-1"></i>{{ $product->location ?? get_phrase('Global') }}
                </p>
                <div class="gv-marketplace-card__actions">
                    <a href="{{ route('single.product', $product->id) }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                        {{ get_phrase('View listing') }}
                    </a>
                </div>
            </div>
        </article>
    </div>
    @if (!empty($search ?? '') && $key === 2)
        @break
    @endif
@endforeach