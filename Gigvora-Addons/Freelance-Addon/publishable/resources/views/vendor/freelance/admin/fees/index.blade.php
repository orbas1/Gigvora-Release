@extends('layouts.admin')

@section('title', 'Fees & Commission')

@section('freelance-content')
<div class="container-fluid py-4" id="admin-fees" data-preview-url="{{ $previewUrl ?? '' }}">
    <h1 class="mb-3">Commission Settings</h1>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Base percentage</label>
                        <input type="number" class="form-control" name="base_percentage" value="{{ $fees['base'] ?? 10 }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum fee</label>
                        <input type="number" class="form-control" name="min_fee" value="{{ $fees['min'] ?? 0 }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Maximum fee</label>
                        <input type="number" class="form-control" name="max_fee" value="{{ $fees['max'] ?? 0 }}">
                    </div>
                    <button class="btn btn-primary" id="save-fees">Save Settings</button>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5>Fee Preview</h5>
                    <p class="text-muted">Enter an example amount to preview commission.</p>
                    <div class="mb-3">
                        <label class="form-label">Example amount</label>
                        <input type="number" class="form-control" id="example-amount" placeholder="500">
                    </div>
                    <div class="p-3 bg-light rounded" id="fee-preview">No preview yet.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/feesPreview.js') }}"></script>
@endpush
