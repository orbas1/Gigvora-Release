@php
    use App\Services\UtilitiesQuickToolsService;

    /** @var UtilitiesQuickToolsService $utilitiesService */
    $utilitiesService = app(UtilitiesQuickToolsService::class);
    $contextKey = strtolower($context ?? ($utilitiesContext ?? UtilitiesQuickToolsService::CONTEXT_GLOBAL));
    $variant = $variant ?? 'card';
    $actions = auth()->check() ? $utilitiesService->actionsFor($contextKey) : [];
    $wrapperTag = $variant === 'compact' ? 'div' : 'section';
    $wrapperClasses = $variant === 'compact'
        ? 'gv-utilities-inline gv-utilities-inline--compact'
        : 'gv-card gv-utilities-inline';
@endphp

@if (!empty($actions))
    <{{ $wrapperTag }} class="{{ $wrapperClasses }}" data-utilities-context="{{ $contextKey }}">
        <div class="gv-utilities-inline__meta">
            <p class="gv-eyebrow mb-1">{{ get_phrase('Utilities') }}</p>
            <div>
                <h3 class="gv-utilities-inline__title">{{ $utilitiesService->labelFor($contextKey) }}</h3>
                <p class="gv-utilities-inline__description">{{ $utilitiesService->descriptionFor($contextKey) }}</p>
            </div>
        </div>

        <div class="gv-utilities-inline__actions">
            @foreach ($actions as $action)
                <a href="{{ $action['href'] }}"
                    class="gv-utilities-inline__action"
                    data-utility-id="{{ $action['id'] }}">
                    <i class="{{ $action['icon'] }}" aria-hidden="true"></i>
                    <span>{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </{{ $wrapperTag }}>
@endif

