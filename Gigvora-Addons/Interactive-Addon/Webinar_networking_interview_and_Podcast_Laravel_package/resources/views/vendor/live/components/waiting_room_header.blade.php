@php
    $statusAttributes = $statusAttributes ?? '';
@endphp

<div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-4">
    <div>
        <h2 class="text-lg font-semibold text-[var(--gv-color-neutral-900)] mb-1">
            {{ $title ?? get_phrase('Waiting Room') }}
        </h2>
        <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">
            {{ get_phrase('Hosted by :host', ['host' => $host ?? get_phrase('Host')]) }}
            • {{ $start ?? get_phrase('TBD') }}
        </p>
    </div>
    <div class="text-right">
        <span class="gv-pill {{ ($status ?? '') === 'Live' ? 'gv-pill--danger' : '' }}" {!! $statusAttributes !!}>
            {{ $status ?? get_phrase('Starting soon') }}
        </span>
        <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0 mt-1">
            {{ get_phrase('Timezone adjusts automatically') }}
        </p>
    </div>
</div>
