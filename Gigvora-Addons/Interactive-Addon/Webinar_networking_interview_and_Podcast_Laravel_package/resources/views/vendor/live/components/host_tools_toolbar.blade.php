<div class="flex flex-wrap gap-2">
    <button class="gv-btn bg-[var(--gv-color-danger)] text-white" data-action="end-session">
        {{ get_phrase('End session') }}
    </button>
    <button class="gv-btn gv-btn-ghost" data-action="toggle-record">
        {{ get_phrase('Toggle recording') }}
    </button>
    <button class="gv-btn gv-btn-ghost" data-action="mute-all">
        {{ get_phrase('Mute all') }}
    </button>
    <button class="gv-btn gv-btn-primary" data-action="share-screen">
        {{ get_phrase('Share screen') }}
    </button>
</div>
