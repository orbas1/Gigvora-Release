<div class="gv-card h-full space-y-3">
    <div class="flex items-center justify-between">
        <span class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Calendar') }}</span>
        <div class="flex gap-2">
            <button class="gv-btn gv-btn-ghost text-xs py-1 px-3" data-nav="prev">{{ get_phrase('Prev') }}</button>
            <button class="gv-btn gv-btn-ghost text-xs py-1 px-3" data-nav="today">{{ get_phrase('Today') }}</button>
            <button class="gv-btn gv-btn-ghost text-xs py-1 px-3" data-nav="next">{{ get_phrase('Next') }}</button>
        </div>
    </div>
    <div id="calendar-widget"
        class="border border-dashed border-[var(--gv-color-border)] rounded-xl py-6 text-center text-sm text-[var(--gv-color-neutral-500)]">
        {{ get_phrase('Calendar placeholder') }}
    </div>
</div>
