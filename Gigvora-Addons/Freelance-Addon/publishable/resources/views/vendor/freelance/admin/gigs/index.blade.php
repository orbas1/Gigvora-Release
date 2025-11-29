@extends('layouts.admin')

@section('title', 'Manage Gigs')

@section('content')
<div class="container-fluid py-4" id="admin-gigs">
    <h1 class="mb-3">Gigs</h1>
    @component('vendor.freelance.components.filter_bar', [
        'filters' => [
            ['label' => 'Status', 'name' => 'status', 'options' => ['' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'disabled' => 'Disabled']],
            ['label' => 'Category', 'name' => 'category', 'options' => $categories ?? []],
        ]
    ])@endcomponent

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Freelancer</th>
                    <th>Status</th>
                    <th>Orders</th>
                    <th>Rating</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($gigs ?? [] as $gig)
                    <tr>
                        <td>{{ $gig['title'] }}</td>
                        <td>{{ $gig['freelancer'] }}</td>
                        <td><span class="badge bg-light text-dark">{{ $gig['status'] }}</span></td>
                        <td>{{ $gig['orders'] }}</td>
                        <td>{{ $gig['rating'] }}</td>
                        <td class="text-end">
                            <button class="btn btn-outline-success btn-sm" data-action="approve" data-id="{{ $gig['id'] }}">Approve</button>
                            <button class="btn btn-outline-danger btn-sm" data-action="disable" data-id="{{ $gig['id'] }}">Disable</button>
                            <button class="btn btn-outline-primary btn-sm" data-action="feature" data-id="{{ $gig['id'] }}">Feature</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No gigs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
