@extends('layouts.app')

@section('title', get_phrase('Billing & credits'))

@section('page-header')
    <div class="space-y-1">
        <p class="gv-eyebrow mb-0">{{ get_phrase('Plan & credits') }}</p>
        <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Manage recruiting spend') }}</h1>
    </div>
@endsection

@section('content')
    <div class="grid gap-4 md:grid-cols-2">
        <div class="gv-card space-y-3">
            <p class="gv-eyebrow mb-0">{{ get_phrase('Current plan') }}</p>
            <h2 class="text-xl font-semibold mb-1">{{ $plan->plan ?? get_phrase('Starter') }}</h2>
            <p class="gv-muted text-sm mb-2">{{ get_phrase('Credits remaining: :count', ['count' => $plan->job_credits ?? 0]) }}</p>
            <button class="gv-btn gv-btn-ghost w-full" id="change-plan">{{ get_phrase('Change plan') }}</button>
        </div>
        <div class="gv-card space-y-3">
            <p class="gv-eyebrow mb-0">{{ get_phrase('Purchase credits') }}</p>
            <p class="gv-muted text-sm mb-2">{{ get_phrase('Top up postings without changing your subscription.') }}</p>
            <div class="flex gap-2">
                <input type="number" class="gv-input" min="1" id="credit-quantity" value="1">
                <button class="gv-btn gv-btn-primary" id="buy-credits">{{ get_phrase('Buy') }}</button>
            </div>
            <div id="plan-options" class="text-sm gv-muted"></div>
        </div>
    </div>

    <section class="gv-card space-y-3 mt-6">
        <h2 class="text-lg font-semibold mb-0">{{ get_phrase('Invoices') }}</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-[var(--gv-color-neutral-500)] border-b border-[var(--gv-color-border)]">
                    <tr>
                        <th class="py-2 pr-4">{{ get_phrase('Date') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Description') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Amount') }}</th>
                        <th class="py-2">{{ get_phrase('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--gv-color-border)]">
                    @forelse($invoices ?? [] as $invoice)
                        <tr>
                            <td class="py-3 pr-4">{{ optional($invoice->date)->format('M d, Y') }}</td>
                            <td class="py-3 pr-4">{{ $invoice->description }}</td>
                            <td class="py-3 pr-4">{{ $invoice->amount }}</td>
                            <td class="py-3">
                                <span class="gv-chip gv-chip-muted">{{ ucfirst($invoice->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center gv-muted">{{ get_phrase('No invoices yet.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection