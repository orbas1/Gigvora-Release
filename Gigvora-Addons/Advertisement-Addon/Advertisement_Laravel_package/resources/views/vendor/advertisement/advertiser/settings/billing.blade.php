@extends('advertisement::layouts.app')

@section('title', 'Billing & Settings')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Billing</li>
    </ol>
</nav>
@endsection

@section('ads-page')
<div class="gv-grid-two">
    <section class="gv-card space-y-4">
        <div>
            <p class="gv-label mb-1">{{ __('Billing profile') }}</p>
            <h5 class="gv-heading text-lg mb-0">{{ __('Company & invoice details') }}</h5>
        </div>
        <form id="billing-form" class="space-y-3">
            <div>
                <label class="gv-label">{{ __('Company name') }}</label>
                <input type="text" name="company" class="gv-input" value="{{ $billing['company'] ?? '' }}" />
            </div>
            <div>
                <label class="gv-label">{{ __('Address') }}</label>
                <textarea name="address" class="gv-input" rows="2">{{ $billing['address'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="gv-label">{{ __('VAT number') }}</label>
                <input type="text" name="vat" class="gv-input" value="{{ $billing['vat'] ?? '' }}" />
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="gv-btn gv-btn-primary">{{ __('Save') }}</button>
                <button type="button" class="gv-btn gv-btn-ghost" id="billing-reset">{{ __('Cancel') }}</button>
            </div>
        </form>
    </section>

    <section class="space-y-4">
        <div class="gv-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <p class="gv-label mb-1">{{ __('Spend limit') }}</p>
                    <h5 class="gv-heading text-lg mb-0">{{ __('Current billing window') }}</h5>
                </div>
                <span class="gv-chip">{{ $billing['limit'] ?? '$0.00' }}</span>
            </div>
            <div class="progress mb-2" style="height: 10px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $billing['usage_percent'] ?? 0 }}%"></div>
            </div>
            <p class="gv-muted small mb-0">{{ __('Used :used of :limit', ['used' => $billing['usage'] ?? '$0', 'limit' => $billing['limit'] ?? '$0']) }}</p>
        </div>
        <div class="gv-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <p class="gv-label mb-1">{{ __('Invoices') }}</p>
                    <h5 class="gv-heading text-lg mb-0">{{ __('Download statements') }}</h5>
                </div>
                <input type="text" class="gv-input gv-input--sm w-auto" id="invoice-date-range" placeholder="{{ __('Date range') }}">
            </div>
            <div class="gv-table-wrapper">
                <table class="gv-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-table-body">
                        @forelse(($invoices ?? []) as $invoice)
                            <tr>
                                <td>{{ $invoice['date'] ?? '-' }}</td>
                                <td>{{ $invoice['description'] ?? '-' }}</td>
                                <td>{{ $invoice['amount'] ?? '$0.00' }}</td>
                                <td><span class="gv-chip gv-chip--ghost">{{ $invoice['status'] ?? 'Paid' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center gv-muted py-3">{{ __('No invoices yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/dashboard.js') }}"></script>
@endpush
