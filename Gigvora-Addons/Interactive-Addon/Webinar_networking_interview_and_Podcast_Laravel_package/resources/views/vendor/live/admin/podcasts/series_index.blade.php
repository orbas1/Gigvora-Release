@extends('layouts.admin')

@section('title', 'Podcast Series')

@section('content')
<div class="container-fluid py-4" id="admin-podcasts">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Podcast Series</h1>
        <input class="form-control w-auto" placeholder="Search" />
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Title</th><th>Host</th><th>Episodes</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <tr><td>Product Stories</td><td>Kim</td><td>12</td><td><span class="badge bg-success">Active</span></td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('wnip.admin.podcasts.series.show', 1) ?? '#' }}">View</a></td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
