@extends('layouts.app')

@section('title', get_phrase('Jobs'))

@section('page-header')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Job postings') }}</h1>
        <a class="gv-btn gv-btn-primary" href="{{ route('employer.jobs.create') }}">
            <i class="fa-solid fa-plus me-2"></i>{{ get_phrase('Post a job') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="gv-card space-y-4" id="employer-jobs-list">
        <div class="flex flex-wrap gap-2">
            @foreach(['draft' => get_phrase('Draft'), 'published' => get_phrase('Open'), 'paused' => get_phrase('Paused'), 'closed' => get_phrase('Closed')] as $value => $label)
                <button class="gv-chip cursor-pointer filter-status" data-status="{{ $value }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-[var(--gv-color-neutral-500)] border-b border-[var(--gv-color-border)]">
                    <tr>
                        <th class="py-2 pr-4">{{ get_phrase('Title') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Created') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Status') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Applications') }}</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--gv-color-border)]">
                    @forelse($jobs as $job)
                        <tr data-status="{{ $job->status }}">
                            <td class="py-3 pr-4">
                                <p class="font-medium text-[var(--gv-color-neutral-900)] mb-1">{{ $job->title }}</p>
                                <p class="gv-muted text-xs mb-0">{{ optional($job->company)->name }}</p>
                            </td>
                            <td class="py-3 pr-4">{{ optional($job->created_at)->format('M d, Y') }}</td>
                            <td class="py-3 pr-4">
                                <span class="gv-chip gv-chip-muted">{{ ucfirst($job->status ?? 'draft') }}</span>
                            </td>
                            <td class="py-3 pr-4">{{ $job->applications_count ?? 0 }}</td>
                            <td class="py-3 text-right space-x-2">
                                <a href="{{ route('jobs.show', $job) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('View') }}</a>
                                <a href="{{ route('employer.jobs.edit', $job) }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Edit') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center gv-muted">{{ get_phrase('No jobs yet. Post your first role to get started.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $jobs->onEachSide(1)->links('vendor.jobs.components.pagination') }}
    </div>
@endsection