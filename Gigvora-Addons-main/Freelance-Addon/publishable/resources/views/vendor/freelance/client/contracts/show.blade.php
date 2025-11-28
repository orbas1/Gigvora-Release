@extends('layouts.app')

@section('title', 'Contract Detail (Client)')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/client/contracts">Contracts</a></li>
        <li class="breadcrumb-item active" aria-current="page">Contract Detail</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="client-contract-detail" data-contract-id="{{ $contract['id'] ?? '' }}">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-1">{{ $contract['title'] ?? '' }}</h3>
                            <p class="text-muted mb-1">Freelancer: {{ $contract['freelancer'] ?? '' }}</p>
                            <small class="text-muted">Total: {{ $contract['amount'] ?? '' }}</small>
                        </div>
                        <span class="badge bg-light text-dark">{{ $contract['status'] ?? '' }}</span>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Milestones</span>
                    <small class="text-muted">Fund → Submit → Release</small>
                </div>
                <div class="card-body">
                    @component('vendor.freelance.components.contract_milestones_timeline', ['milestones' => $contract['milestones'] ?? []])@endcomponent
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Messages</div>
                <div class="card-body" id="contract-messages">
                    @forelse($messages ?? [] as $message)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $message['author'] }}</strong>
                                <small class="text-muted">{{ $message['time'] }}</small>
                            </div>
                            <p class="mb-1">{{ $message['body'] }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No messages.</p>
                    @endforelse
                </div>
                <div class="card-footer">
                    <form id="contract-message-form" class="d-flex gap-2">
                        <input type="text" class="form-control" name="message" placeholder="Write a message">
                        <button class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Escrow</h6>
                    <p class="mb-1">Balance funded: {{ $contract['escrow'] ?? '$0' }}</p>
                    <p class="mb-0">Status: <span class="badge bg-light text-dark">{{ $contract['escrow_status'] ?? 'Pending' }}</span></p>
                </div>
            </div>
            <div class="card">
                <div class="card-body d-grid gap-2">
                    <button class="btn btn-outline-success" data-action="fund-milestone">Fund Milestone</button>
                    <button class="btn btn-outline-primary" data-action="release-payment">Release Payment</button>
                    <button class="btn btn-outline-danger" data-action="request-refund">Request Refund</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/contractDetail.js') }}"></script>
@endpush
