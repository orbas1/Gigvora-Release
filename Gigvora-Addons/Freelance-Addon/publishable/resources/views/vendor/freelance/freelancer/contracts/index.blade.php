@extends('freelance::layouts.freelance')

@section('title', 'Contracts')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/freelancer/dashboard">Freelancer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Contracts</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="container py-4" id="freelancer-contracts">
    <div class="d-flex align-items-center mb-3">
        <h2 class="mb-0">Contracts</h2>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Project/Gig</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Next milestone</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($contracts ?? [] as $contract)
                    <tr>
                        <td>{{ $contract['client'] }}</td>
                        <td>{{ $contract['title'] }}</td>
                        <td>{{ $contract['amount'] }}</td>
                        <td><span class="badge bg-light text-dark">{{ $contract['status'] }}</span></td>
                        <td>{{ $contract['next_milestone'] ?? 'N/A' }}</td>
                        <td class="text-end"><a class="btn btn-outline-primary btn-sm" href="{{ $contract['url'] ?? '#' }}">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No contracts.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
