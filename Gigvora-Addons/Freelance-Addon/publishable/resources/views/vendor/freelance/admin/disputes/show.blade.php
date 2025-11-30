@extends('layouts.admin')

@section('title', 'Dispute Review')

@section('freelance-content')
<div class="container-fluid py-4" id="admin-dispute-detail">
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-1">{{ $dispute['contract'] ?? '' }}</h4>
            <p class="text-muted mb-0">Parties: {{ $dispute['parties'] ?? '' }}</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">Statements</div>
                <div class="card-body">
                    <h6>Client</h6>
                    <p>{{ $dispute['client_statement'] ?? '' }}</p>
                    <h6>Freelancer</h6>
                    <p>{{ $dispute['freelancer_statement'] ?? '' }}</p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">Evidence</div>
                <div class="card-body">
                    <ul class="mb-0">
                        @forelse(($dispute['evidence'] ?? []) as $item)
                            <li><a href="{{ $item['url'] }}" target="_blank">{{ $item['label'] }}</a></li>
                        @empty
                            <li class="text-muted">No evidence attached.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Messages</div>
                <div class="card-body">
                    @forelse($messages ?? [] as $message)
                        <div class="mb-2">
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
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6>Admin Actions</h6>
                    <textarea class="form-control mb-2" rows="3" placeholder="Internal notes"></textarea>
                    <button class="btn btn-outline-success w-100 mb-2" data-action="refund">Refund</button>
                    <button class="btn btn-outline-primary w-100 mb-2" data-action="split">Split</button>
                    <button class="btn btn-outline-danger w-100" data-action="release">Release to freelancer</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
