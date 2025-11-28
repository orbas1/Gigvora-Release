@extends('layouts.app')

@section('title', 'Billing & Settings')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Billing</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Billing Profile</h5>
            </div>
            <div class="card-body">
                <form id="billing-form">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company" class="form-control" value="{{ $billing['company'] ?? '' }}" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ $billing['address'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">VAT Number</label>
                        <input type="text" name="vat" class="form-control" value="{{ $billing['vat'] ?? '' }}" />
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-outline-secondary" id="billing-reset">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Spend Limit</h5>
                    <small class="text-muted">Track how much you can still spend this period.</small>
                </div>
                <span class="badge bg-light text-dark">{{ $billing['limit'] ?? '$0.00' }}</span>
            </div>
            <div class="card-body">
                <div class="progress mb-2" style="height: 10px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $billing['usage_percent'] ?? 0 }}%"></div>
                </div>
                <p class="text-muted small mb-0">Used {{ $billing['usage'] ?? '$0' }} of {{ $billing['limit'] ?? '$0' }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Invoices</h5>
                <input type="text" class="form-control form-control-sm" id="invoice-date-range" placeholder="Date range" style="width: 180px;">
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-table-body">
                        @forelse(($invoices ?? []) as $invoice)
                            <tr>
                                <td>{{ $invoice['date'] ?? '-' }}</td>
                                <td>{{ $invoice['description'] ?? '-' }}</td>
                                <td>{{ $invoice['amount'] ?? '$0.00' }}</td>
                                <td><span class="badge bg-light text-dark">{{ $invoice['status'] ?? 'Paid' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No invoices yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/dashboard.js') }}"></script>
@endpush
