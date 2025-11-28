@extends('layouts.app')

@section('title', 'Company Profile')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Company</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="company-profile-form">
    <h1 class="h4 mb-3">Company Profile</h1>
    <form method="post" action="{{ route('employer.company.update') }}" class="card">
        @csrf
        @method('put')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Company name</label><input class="form-control" name="name" value="{{ $company->name ?? '' }}" required></div>
                <div class="col-md-6"><label class="form-label">Website</label><input class="form-control" name="website" value="{{ $company->website ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label">Location</label><input class="form-control" name="location" value="{{ $company->location ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label">Industry</label><input class="form-control" name="industry" value="{{ $company->industry ?? '' }}"></div>
                <div class="col-md-6"><label class="form-label">Size</label><input class="form-control" name="size" value="{{ $company->size ?? '' }}"></div>
                <div class="col-12"><label class="form-label">About</label><textarea class="form-control" rows="4" name="about">{{ $company->about ?? '' }}</textarea></div>
                <div class="col-12"><label class="form-label">Culture & Benefits</label><textarea class="form-control" rows="4" name="culture">{{ $company->culture ?? '' }}</textarea></div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>
@endsection
