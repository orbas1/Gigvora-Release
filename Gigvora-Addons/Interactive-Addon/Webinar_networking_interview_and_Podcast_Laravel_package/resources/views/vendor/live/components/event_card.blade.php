@php
    use Illuminate\Support\Str;

    $title = $title ?? get_phrase('Live event');
    $description = $description ?? null;
    $meta = $meta ?? null;
    $caption = $caption ?? null;
    $status = $status ?? null;
    $tag = $tag ?? null;
    $cta = $cta ?? get_phrase('View details');
    $href = $href ?? '#';
@endphp

<article class="gv-card space-y-3">
    <div class="flex items-start justify-between gap-3">
        <div class="space-y-1">
            @if (!empty($eyebrow))
                <p class="gv-eyebrow mb-1">{{ $eyebrow }}</p>
            @endif
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">
                {{ $title }}
            </h3>
            <div class="text-sm text-[var(--gv-color-neutral-500)] flex flex-wrap gap-2">
                @if ($caption)
                    <span>{{ $caption }}</span>
                @endif
                @if ($meta)
                    <span>{{ $meta }}</span>
                @endif
            </div>
        </div>
        <div class="flex flex-col items-end gap-2 text-sm">
            @if ($status)
                <span class="gv-pill {{ Str::of($status)->lower()->contains('live') ? 'gv-pill--danger' : '' }}">
                    {{ $status }}
                </span>
            @endif
            @if ($tag)
                <span class="gv-pill gv-pill--success">
                    {{ $tag }}
                </span>
            @endif
        </div>
    </div>

    @if ($description)
        <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
            {{ $description }}
        </p>
    @endif

    <div class="flex items-center justify-between gap-3">
        <div class="text-sm text-[var(--gv-color-neutral-500)]">
            {{ $detail ?? '' }}
        </div>
        <a href="{{ $href }}" class="gv-btn gv-btn-ghost">
            {{ $cta }}
        </a>
    </div>
</article>
