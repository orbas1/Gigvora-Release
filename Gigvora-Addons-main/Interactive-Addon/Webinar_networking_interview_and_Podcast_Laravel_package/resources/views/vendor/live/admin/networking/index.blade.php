@extends('layouts.admin')

@section('title', 'Networking Sessions')

@section('content')
<div class="container-fluid py-4" id="admin-networking">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Networking</h1>
        <input class="form-control w-auto" placeholder="Search" />
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Title</th><th>Host</th><th>Date</th><th>Type</th><th>Reports</th><th></th></tr></thead>
            <tbody>
                <tr><td>Fintech founders</td><td>Nia</td><td>May 4</td><td>Speed</td><td>1</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('wnip.admin.networking.show', 1) ?? '#' }}">View</a></td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
