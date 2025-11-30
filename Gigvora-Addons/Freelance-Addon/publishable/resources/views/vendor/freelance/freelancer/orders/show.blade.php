@extends('freelance::layouts.freelance')

@section('title', 'Order Detail')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/freelancer/orders">Orders</a></li>
        <li class="breadcrumb-item active" aria-current="page">Order Detail</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="container py-4" id="order-detail" data-order-id="{{ $order['id'] ?? '' }}">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-1">{{ $order['gig'] ?? 'Gig title' }}</h4>
                    <p class="text-muted">Client: {{ $order['client'] ?? '' }}</p>
                    <div class="d-flex gap-3 small text-muted">
                        <span>Due: {{ $order['due_date'] ?? '' }}</span>
                        <span>Amount: {{ $order['amount'] ?? '' }}</span>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Requirements</span>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        @foreach($order['requirements'] ?? [] as $req)
                            <li>{{ $req }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Messages & Attachments</div>
                <div class="card-body" id="message-thread">
                    @forelse($messages ?? [] as $message)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $message['author'] }}</strong>
                                <small class="text-muted">{{ $message['time'] }}</small>
                            </div>
                            <p class="mb-1">{{ $message['body'] }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No messages yet.</p>
                    @endforelse
                </div>
                <div class="card-footer">
                    <form id="message-form" class="d-flex gap-2">
                        <input type="text" class="form-control" name="message" placeholder="Type a message">
                        <button class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Deliveries</div>
                <div class="card-body">
                    @forelse($deliveries ?? [] as $delivery)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $delivery['title'] }}</strong>
                                <span class="badge bg-light text-dark">{{ $delivery['status'] }}</span>
                            </div>
                            <p class="mb-1">{{ $delivery['notes'] }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No deliveries submitted.</p>
                    @endforelse
                    <button class="btn btn-success" id="deliver-work">Deliver Work</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Escrow</h6>
                    <p class="mb-1">Status: <span class="badge bg-light text-dark">{{ $order['escrow_status'] ?? 'Pending' }}</span></p>
                    <p class="mb-0">Funded amount: {{ $order['amount'] ?? '$0' }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body d-grid gap-2">
                    <button class="btn btn-outline-primary" id="open-dispute">Open Dispute</button>
                    <button class="btn btn-outline-secondary" id="request-change">Request Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/orderDetail.js') }}"></script>
@endpush
