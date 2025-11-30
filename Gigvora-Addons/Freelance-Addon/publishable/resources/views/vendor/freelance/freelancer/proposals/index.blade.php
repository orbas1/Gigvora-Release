@extends('freelance::layouts.freelance')

@section('title', 'My Proposals')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/freelancer/dashboard">Freelancer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Proposals</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="container py-4" id="proposals-list">
    <div class="d-flex align-items-center mb-3">
        <h2 class="mb-0">My Proposals</h2>
    </div>
    <ul class="nav nav-tabs mb-3" id="proposal-tabs">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'accepted' => 'Accepted', 'rejected' => 'Rejected'] as $key => $label)
            <li class="nav-item"><a class="nav-link {{ $key === 'all' ? 'active' : '' }}" data-status="{{ $key }}" href="#">{{ $label }}</a></li>
        @endforeach
    </ul>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Client</th>
                    <th>Your bid</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="proposals-body">
                @forelse($proposals ?? [] as $proposal)
                    <tr>
                        <td>{{ $proposal['project'] }}</td>
                        <td>{{ $proposal['client'] }}</td>
                        <td>{{ $proposal['amount'] }}</td>
                        <td><span class="badge bg-light text-dark">{{ $proposal['status'] }}</span></td>
                        <td>{{ $proposal['date'] }}</td>
                        <td class="text-end">
                            <a href="{{ $proposal['view_url'] ?? '#' }}" class="btn btn-outline-primary btn-sm">View</a>
                            @if(($proposal['can_withdraw'] ?? false))
                                <button class="btn btn-outline-danger btn-sm" data-action="withdraw" data-id="{{ $proposal['id'] }}">Withdraw</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No proposals yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/proposalsList.js') }}"></script>
@endpush
