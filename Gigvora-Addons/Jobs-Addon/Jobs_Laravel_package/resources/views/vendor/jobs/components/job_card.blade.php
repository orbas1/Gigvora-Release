@php use Illuminate\Support\Str; @endphp
<article class="gv-card flex flex-col gap-3 lg:flex-row lg:items-center" data-job-id="{{ $job->id ?? '' }}">
    <div class="flex-1 space-y-2">
        <div class="flex items-start gap-3">
            <div class="h-12 w-12 rounded-full bg-[var(--gv-color-neutral-100)] border border-[var(--gv-color-border)] flex items-center justify-center text-[var(--gv-color-neutral-500)] font-semibold uppercase">
                {{ Str::substr(optional($job->company)->name, 0, 2) }}
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-semibold text-[var(--gv-color-neutral-900)] mb-0">
                    <a href="{{ route('jobs.show', $job->id) }}" class="text-reset">{{ $job->title ?? get_phrase('Job title') }}</a>
                </h3>
                <p class="gv-muted text-sm mb-0">
                    {{ optional($job->company)->name ?? get_phrase('Company') }} · {{ $job->location ?? get_phrase('Remote') }}
                </p>
                <p class="text-sm font-medium text-[var(--gv-color-neutral-800)] mb-0">
                    {{ $job->salary_label ?? get_phrase('Competitive salary') }}
                </p>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach(($job->tag_list ?? []) as $tag)
                <span class="gv-chip">{{ $tag }}</span>
            @endforeach
            @if(($job->published_at ?? null))
                <span class="gv-chip gv-chip-muted">
                    {{ get_phrase('Posted') }} {{ optional($job->published_at)->diffForHumans() }}
                </span>
            @endif
        </div>
    </div>

    @if($showActions ?? true)
        <div class="flex flex-row lg:flex-col items-center gap-2">
            <a href="{{ route('jobs.show', $job->id) }}" class="gv-btn gv-btn-secondary w-full">
                {{ get_phrase('View details') }}
            </a>
            <button class="gv-btn gv-btn-ghost save-job w-full" data-job-id="{{ $job->id ?? '' }}">
                <i class="fa-regular fa-bookmark me-2"></i>{{ get_phrase('Save') }}
            </button>
        </div>
    @endif
</article>
