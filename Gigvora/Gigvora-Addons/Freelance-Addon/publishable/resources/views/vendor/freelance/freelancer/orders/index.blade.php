@extends('layouts.app')

@section('title', 'Gig Orders')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/freelance/freelancer/dashboard">Freelancer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Gig Orders</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="gig-orders" data-fetch-url="{{ $fetchUrl ?? '' }}">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Orders</h2>
    </div>
    <div class="d-flex gap-2 mb-3">
        @foreach(['in_progress' => 'In Progress', 'delivered' => 'Delivered', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'dispute' => 'In Dispute'] as $value => $label)
            <button class="btn btn-sm btn-outline-secondary status-filter" data-value="{{ $value }}">{{ $label }}</button>
        @endforeach
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Gig</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Due date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders ?? [] as $order)
                    <tr>
                        <td>{{ $order['client'] }}</td>
                        <td>{{ $order['gig'] }}</td>
                        <td>{{ $order['amount'] }}</td>
                        <td><span class="badge bg-light text-dark">{{ $order['status'] }}</span></td>
                        <td>{{ $order['due_date'] }}</td>
                        <td class="text-end">
                            <a class="btn btn-outline-primary btn-sm" href="{{ $order['url'] ?? '#' }}">View</a>
                            <button class="btn btn-outline-danger btn-sm">Open Dispute</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/ordersList.js') }}"></script>
@endpush
