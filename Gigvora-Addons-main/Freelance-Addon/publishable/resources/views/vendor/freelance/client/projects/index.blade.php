@extends('layouts.app')

@section('title', 'My Projects')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/client/dashboard">Client Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Projects</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="client-projects" data-fetch-url="{{ $fetchUrl ?? '' }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">My Projects</h2>
        <a href="/freelance/client/projects/create" class="btn btn-primary">Post Project</a>
    </div>
    @component('vendor.freelance.components.filter_bar', [
        'filters' => [
            ['label' => 'Status', 'name' => 'status', 'options' => ['' => 'All', 'draft' => 'Draft', 'open' => 'Open', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'closed' => 'Closed'], 'value' => request('status')]
        ]
    ])@endcomponent

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Proposals</th>
                    <th>Budget</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects ?? [] as $project)
                    <tr>
                        <td>{{ $project['title'] }}</td>
                        <td>{{ $project['proposals_count'] }}</td>
                        <td>{{ $project['budget'] }}</td>
                        <td><span class="badge bg-light text-dark">{{ $project['status'] }}</span></td>
                        <td class="text-end">
                            <a href="{{ $project['view_url'] ?? '#' }}" class="btn btn-outline-primary btn-sm">View</a>
                            <a href="{{ $project['edit_url'] ?? '#' }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No projects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
