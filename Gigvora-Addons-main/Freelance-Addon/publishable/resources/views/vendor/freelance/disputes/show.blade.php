@extends('layouts.app')

@section('title', 'Dispute Detail')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/disputes">Disputes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dispute Detail</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="dispute-detail" data-dispute-id="{{ $dispute['id'] ?? '' }}">
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-1">{{ $dispute['title'] ?? '' }}</h4>
            <p class="text-muted mb-1">Reason: {{ $dispute['reason'] ?? '' }}</p>
            <p class="text-muted mb-0">Status: <span class="badge bg-light text-dark">{{ $dispute['status'] ?? '' }}</span></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Statements</div>
        <div class="card-body">
            <div class="mb-3">
                <h6>Client</h6>
                <p class="mb-0">{{ $dispute['client_statement'] ?? '' }}</p>
            </div>
            <div>
                <h6>Freelancer</h6>
                <p class="mb-0">{{ $dispute['freelancer_statement'] ?? '' }}</p>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Evidence</div>
        <div class="card-body">
            <ul class="mb-0">
                @forelse($dispute['evidence'] ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" target="_blank">{{ $item['label'] }}</a></li>
                @empty
                    <li class="text-muted">No evidence shared.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Messages</div>
        <div class="card-body" id="dispute-messages">
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
            <form id="dispute-message-form" class="d-flex gap-2">
                <input type="text" class="form-control" name="message" placeholder="Add a statement">
                <button class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/disputeCentre.js') }}"></script>
@endpush
