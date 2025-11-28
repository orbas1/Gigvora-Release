@extends('layouts.admin')

@section('title', 'Job Locations')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Locations</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid py-4" id="admin-locations">
    <div class="row">
        <div class="col-lg-4">
            <form class="card" method="post" action="{{ route('admin.locations.store') }}">
                @csrf
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Add Location</h6>
                    <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Existing</h6>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light"><tr><th>Name</th><th></th></tr></thead>
                            <tbody>
                                @foreach(($locations ?? []) as $location)
                                    <tr>
                                        <td>{{ $location->name }}</td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a class="btn btn-outline-secondary" href="{{ route('admin.locations.edit', $location->id) }}">Edit</a>
                                                <form action="{{ route('admin.locations.destroy', $location->id) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-outline-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if(empty($locations))
                                    <tr><td colspan="2" class="text-center text-muted py-4">No locations.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
