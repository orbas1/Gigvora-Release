<div class="row g-3 dashboard-kpis">
    @foreach($kpis as $kpi)
        <div class="col-6 col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">{{ $kpi['label'] }}</p>
                    <h4 class="mb-2">{{ $kpi['value'] }}</h4>
                    @isset($kpi['helper'])
                        <small class="text-muted">{{ $kpi['helper'] }}</small>
                    @endisset
                </div>
            </div>
        </div>
    @endforeach
</div>
