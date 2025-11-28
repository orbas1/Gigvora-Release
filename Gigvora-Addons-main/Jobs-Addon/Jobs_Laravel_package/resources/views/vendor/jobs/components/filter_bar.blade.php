<div class="card">
    <div class="card-header bg-white fw-semibold">Filters</div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Job type</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach(['Full-time','Part-time','Contract','Remote'] as $type)
                    <div class="form-check">
                        <input class="form-check-input filter-input" type="checkbox" value="{{ strtolower($type) }}" id="type-{{ strtolower($type) }}">
                        <label class="form-check-label" for="type-{{ strtolower($type) }}">{{ $type }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Salary range</label>
            <input type="range" class="form-range" min="0" max="200000" step="1000" id="salary-range">
            <div class="d-flex justify-content-between text-muted small">
                <span>$0</span><span id="salary-output">$100k+</span>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Experience level</label>
            <select class="form-select" id="experience-filter">
                <option value="">Any</option>
                <option value="junior">Junior</option>
                <option value="mid">Mid</option>
                <option value="senior">Senior</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Industry</label>
            <select class="form-select" id="industry-filter">
                <option value="">All industries</option>
                <option>Technology</option>
                <option>Finance</option>
                <option>Health</option>
                <option>Education</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Posted date</label>
            <select class="form-select" id="posted-date-filter">
                <option value="">Anytime</option>
                <option value="24h">Last 24 hours</option>
                <option value="week">This week</option>
                <option value="month">This month</option>
            </select>
        </div>
        <button class="btn btn-primary w-100" id="apply-filters">Apply filters</button>
    </div>
</div>
