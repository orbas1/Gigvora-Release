<div class="row g-3">
    @foreach($kpis as $kpi)
        <div class="col-6 col-lg-3">
            <article class="gv-card h-100 space-y-1">
                <p class="gv-muted text-uppercase text-xs mb-1">{{ $kpi['label'] }}</p>
                <h4 class="mb-0 text-2xl">{{ $kpi['value'] }}</h4>
                @isset($kpi['helper'])
                    <small class="gv-muted">{{ $kpi['helper'] }}</small>
                @endisset
            </article>
        </div>
    @endforeach
</div>
