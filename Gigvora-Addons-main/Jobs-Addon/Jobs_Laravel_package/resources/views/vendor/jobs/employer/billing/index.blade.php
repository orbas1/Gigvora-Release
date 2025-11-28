@extends('layouts.app')

@section('title', 'Billing & Credits')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Billing</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="billing-page">
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Current Plan</h6>
                    <p class="h5 mb-1">{{ $plan->name ?? 'Starter' }}</p>
                    <p class="text-muted">{{ $plan->description ?? 'Great for growing teams.' }}</p>
                    <p class="mb-0">Credits remaining: <strong>{{ $plan->credits ?? 0 }}</strong></p>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-outline-primary" id="change-plan">Change plan</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Purchase Credits</h6>
                    <p class="text-muted">Buy additional job postings as needed.</p>
                    <div class="input-group mb-2">
                        <input type="number" class="form-control" min="1" id="credit-quantity" value="1">
                        <button class="btn btn-primary" id="buy-credits">Buy</button>
                    </div>
                    <div id="plan-options"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h6 class="text-muted text-uppercase">Invoices</h6>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>Date</th><th>Description</th><th>Amount</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse(($invoices ?? []) as $invoice)
                            <tr>
                                <td>{{ optional($invoice->date)->format('M d, Y') }}</td>
                                <td>{{ $invoice->description }}</td>
                                <td>{{ $invoice->amount }}</td>
                                <td><span class="badge bg-light text-dark">{{ $invoice->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No invoices yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
