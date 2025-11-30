<section class="gv-card space-y-4">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="text-lg font-semibold mb-1">{{ get_phrase('Interview calendar') }}</h2>
            <p class="gv-muted text-sm mb-0">{{ get_phrase('Select a date to review or schedule interviews.') }}</p>
        </div>
        <div class="flex gap-2">
            <button class="gv-btn gv-btn-ghost gv-btn-sm" data-calendar-nav="prev">{{ get_phrase('Prev') }}</button>
            <button class="gv-btn gv-btn-ghost gv-btn-sm" data-calendar-nav="today">{{ get_phrase('Today') }}</button>
            <button class="gv-btn gv-btn-ghost gv-btn-sm" data-calendar-nav="next">{{ get_phrase('Next') }}</button>
        </div>
    </div>
    <div id="interview-calendar" class="space-y-3" data-events='@json($events ?? [])'></div>
</section>