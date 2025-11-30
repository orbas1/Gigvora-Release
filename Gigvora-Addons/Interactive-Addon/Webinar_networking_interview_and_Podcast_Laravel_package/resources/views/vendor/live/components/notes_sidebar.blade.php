<div class="gv-card space-y-3 h-full">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-0">
            {{ get_phrase('Notes') }}
        </h3>
        <span class="text-xs text-[var(--gv-color-neutral-500)]">
            {{ get_phrase('Private to hosts') }}
        </span>
    </div>
    <textarea class="gv-input min-h-[160px]" id="notes-content"
        placeholder="{{ get_phrase('Capture quick notes here...') }}">{{ $notes ?? '' }}</textarea>
    <button class="gv-btn gv-btn-primary w-full" id="notes-save">
        {{ get_phrase('Save notes') }}
    </button>
</div>
