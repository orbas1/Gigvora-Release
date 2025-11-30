@extends('layouts.admin')

@section('title', 'Manage Disputes')

@section('freelance-content')
<div class="container-fluid py-4" id="admin-disputes">
    <h1 class="mb-3">Disputes</h1>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Contract</th>
                    <th>Parties</th>
                    <th>Status</th>
                    <th>Opened</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($disputes ?? [] as $dispute)
                    <tr>
                        <td>{{ $dispute['contract'] }}</td>
                        <td>{{ $dispute['parties'] }}</td>
                        <td><span class="badge bg-light text-dark">{{ $dispute['status'] }}</span></td>
                        <td>{{ $dispute['opened'] }}</td>
                        <td class="text-end"><a href="{{ $dispute['url'] ?? '#' }}" class="btn btn-outline-primary btn-sm">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No disputes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
