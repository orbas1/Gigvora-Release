@extends('layouts.app')

@section('title', get_phrase('Interviews'))

@section('page-header')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Interviews') }}</h1>
        <a href="{{ route('employer.interviews.calendar') }}" class="gv-btn gv-btn-ghost">
            <i class="fa-regular fa-calendar me-2"></i>{{ get_phrase('Calendar view') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="gv-card space-y-3" id="interviews-list">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-[var(--gv-color-neutral-500)] border-b border-[var(--gv-color-border)]">
                    <tr>
                        <th class="py-2 pr-4">{{ get_phrase('Candidate') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Role') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Date & time') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Status') }}</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--gv-color-border)]">
                    @forelse($interviews as $interview)
                        <tr>
                            <td class="py-3 pr-4">{{ optional($interview->application->candidate->user)->name }}</td>
                            <td class="py-3 pr-4">{{ optional($interview->application->job)->title }}</td>
                            <td class="py-3 pr-4">{{ optional($interview->scheduled_at)->format('M d, Y h:i A') }}</td>
                            <td class="py-3 pr-4">
                                <span class="gv-chip gv-chip-muted">{{ ucfirst($interview->status ?? 'scheduled') }}</span>
                            </td>
                            <td class="py-3 text-right space-x-2">
                                <button class="gv-btn gv-btn-ghost gv-btn-sm reschedule" data-id="{{ $interview->id }}">{{ get_phrase('Reschedule') }}</button>
                                <button class="gv-btn gv-btn-ghost gv-btn-sm cancel text-[var(--gv-color-danger-600)]" data-id="{{ $interview->id }}">{{ get_phrase('Cancel') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center gv-muted">{{ get_phrase('No interviews scheduled.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('resources/js/jobs/interviewCalendar.js') }}"></script>
@endpush