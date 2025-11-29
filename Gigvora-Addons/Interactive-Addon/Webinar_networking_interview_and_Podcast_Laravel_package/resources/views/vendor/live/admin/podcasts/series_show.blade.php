@extends('layouts.admin')

@section('title', 'Podcast Series Detail')

@section('content')
<div class="container-fluid py-4" id="admin-podcast-series">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Product Stories</h1>
            <div class="text-muted">Host: Kim</div>
        </div>
        <button class="btn btn-outline-primary">New Episode</button>
    </div>

    <div class="card mb-3">
        <div class="card-header">Episodes</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>Title</th><th>Date</th><th>Status</th><th>Explicit</th><th></th></tr></thead>
                <tbody>
                    <tr><td>Episode 1</td><td>Apr 1</td><td><span class="badge bg-success">Published</span></td><td>No</td><td class="text-end"><button class="btn btn-sm btn-outline-secondary">Feature</button></td></tr>
                    <tr><td>Episode 2</td><td>Apr 8</td><td><span class="badge bg-warning">Pending</span></td><td>Yes</td><td class="text-end"><div class="btn-group btn-group-sm"><button class="btn btn-outline-success">Approve</button><button class="btn btn-outline-danger">Reject</button></div></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
