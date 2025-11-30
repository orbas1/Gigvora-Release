<div class="gv-card space-y-5">
    <div>
        <h3 class="text-lg font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ get_phrase('Filters') }}</h3>
        <p class="gv-muted text-sm mb-0">{{ get_phrase('Refine by job type, pay, experience, and posting date.') }}</p>
    </div>

    <div class="space-y-4">
        <div class="space-y-2">
            <label class="gv-label">{{ get_phrase('Job type') }}</label>
            <div class="flex flex-wrap gap-2">
                @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'contract' => 'Contract', 'remote' => 'Remote'] as $value => $label)
                    <label class="inline-flex items-center gap-2 rounded-full border border-[var(--gv-color-border)] px-3 py-1 text-sm cursor-pointer hover:border-[var(--gv-color-primary-400)]">
                        <input type="checkbox" class="filter-input sr-only" value="{{ $value }}" id="type-{{ $value }}">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="space-y-2">
            <label class="gv-label" for="salary-range">{{ get_phrase('Salary range') }}</label>
            <input type="range" min="0" max="200000" step="1000" id="salary-range"
                   class="w-full accent-[var(--gv-color-primary-500)]">
            <div class="flex items-center justify-between text-xs text-[var(--gv-color-neutral-500)]">
                <span>$0</span>
                <span id="salary-output">$100k+</span>
            </div>
        </div>

        <div class="space-y-2">
            <label class="gv-label" for="experience-filter">{{ get_phrase('Experience level') }}</label>
            <select id="experience-filter" class="gv-input">
                <option value="">{{ get_phrase('Any') }}</option>
                <option value="junior">{{ get_phrase('Junior') }}</option>
                <option value="mid">{{ get_phrase('Mid') }}</option>
                <option value="senior">{{ get_phrase('Senior') }}</option>
            </select>
        </div>

        <div class="space-y-2">
            <label class="gv-label" for="industry-filter">{{ get_phrase('Industry') }}</label>
            <select id="industry-filter" class="gv-input">
                <option value="">{{ get_phrase('All industries') }}</option>
                <option value="technology">{{ get_phrase('Technology') }}</option>
                <option value="finance">{{ get_phrase('Finance') }}</option>
                <option value="health">{{ get_phrase('Health') }}</option>
                <option value="education">{{ get_phrase('Education') }}</option>
            </select>
        </div>

        <div class="space-y-2">
            <label class="gv-label" for="posted-date-filter">{{ get_phrase('Posted date') }}</label>
            <select id="posted-date-filter" class="gv-input">
                <option value="">{{ get_phrase('Anytime') }}</option>
                <option value="24h">{{ get_phrase('Last 24 hours') }}</option>
                <option value="week">{{ get_phrase('This week') }}</option>
                <option value="month">{{ get_phrase('This month') }}</option>
            </select>
        </div>
    </div>

    <button class="gv-btn gv-btn-primary w-full" id="apply-filters">
        <i class="fa-solid fa-sliders me-2"></i>{{ get_phrase('Apply filters') }}
    </button>
</div>
