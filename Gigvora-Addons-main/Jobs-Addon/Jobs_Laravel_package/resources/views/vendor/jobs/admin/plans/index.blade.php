@extends('layouts.admin')

@section('title', 'Job Plans')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Plans</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid py-4" id="admin-plans">
    <div class="row">
        <div class="col-lg-4">
            <form class="card" method="post" action="{{ route('admin.plans.store') }}">
                @csrf
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Add Plan</h6>
                    <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
                    <div class="mb-3"><label class="form-label">Price</label><input class="form-control" name="price" type="number" step="0.01"></div>
                    <div class="mb-3"><label class="form-label">Number of jobs</label><input class="form-control" name="jobs_count" type="number"></div>
                    <div class="mb-3"><label class="form-label">Duration (days)</label><input class="form-control" name="duration" type="number"></div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="active" id="plan-active" checked>
                        <label class="form-check-label" for="plan-active">Active</label>
                    </div>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Plans</h6>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light"><tr><th>Name</th><th>Price</th><th>Jobs</th><th>Duration</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                @foreach(($plans ?? []) as $plan)
                                    <tr>
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ $plan->price }}</td>
                                        <td>{{ $plan->jobs_count }}</td>
                                        <td>{{ $plan->duration }} days</td>
                                        <td><span class="badge bg-light text-dark">{{ $plan->active ? 'Active' : 'Inactive' }}</span></td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a class="btn btn-outline-secondary" href="{{ route('admin.plans.edit', $plan->id) }}">Edit</a>
                                                <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-outline-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if(empty($plans))
                                    <tr><td colspan="6" class="text-center text-muted py-4">No plans configured.</td></tr>
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
