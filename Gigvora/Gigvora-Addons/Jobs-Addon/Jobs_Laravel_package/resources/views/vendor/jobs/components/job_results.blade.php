@forelse($jobs as $job)
    @include('vendor.jobs.components.job_card', ['job' => $job, 'showActions' => true])
@empty
    <div class="alert alert-light border">No jobs found. Try adjusting your filters.</div>
@endforelse
@include('vendor.jobs.components.pagination', ['paginator' => $jobs])
