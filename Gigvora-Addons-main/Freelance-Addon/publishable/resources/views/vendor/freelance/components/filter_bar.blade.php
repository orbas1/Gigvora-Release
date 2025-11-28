<div class="d-flex flex-wrap align-items-center gap-2 mb-3 filter-bar">
    @foreach($filters as $filter)
        <div class="d-flex align-items-center gap-2">
            <label class="text-muted small mb-0">{{ $filter['label'] }}</label>
            <select class="form-select form-select-sm" name="{{ $filter['name'] }}">
                @foreach($filter['options'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filter['value'] ?? '') == $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    @endforeach
    @isset($showSearch)
        <div class="ms-auto flex-grow-1" style="min-width:200px;">
            <input type="search" class="form-control form-control-sm" placeholder="Search" name="search" value="{{ $search ?? '' }}">
        </div>
    @endisset
</div>
