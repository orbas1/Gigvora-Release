@extends('layouts.admin')

@section('title', 'Manage Projects')

@section('freelance-content')
<div class="container-fluid py-4" id="admin-projects">
    <h1 class="mb-3">Projects</h1>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Client</th>
                    <th>Status</th>
                    <th>Proposals</th>
                    <th>Budget</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects ?? [] as $project)
                    <tr>
                        <td>{{ $project['title'] }}</td>
                        <td>{{ $project['client'] }}</td>
                        <td><span class="badge bg-light text-dark">{{ $project['status'] }}</span></td>
                        <td>{{ $project['proposals'] }}</td>
                        <td>{{ $project['budget'] }}</td>
                        <td class="text-end">
                            <button class="btn btn-outline-danger btn-sm" data-action="close" data-id="{{ $project['id'] }}">Close</button>
                            <button class="btn btn-outline-warning btn-sm" data-action="flag" data-id="{{ $project['id'] }}">Flag</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">No projects found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
