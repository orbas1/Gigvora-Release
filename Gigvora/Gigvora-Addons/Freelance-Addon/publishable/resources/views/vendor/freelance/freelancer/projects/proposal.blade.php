@extends('layouts.app')

@section('title', 'Submit Proposal')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/freelancer/projects">Projects</a></li>
        <li class="breadcrumb-item active" aria-current="page">Submit Proposal</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="proposal-form" data-save-url="{{ $saveUrl ?? '' }}">
    <div class="card">
        <div class="card-body">
            <h3 class="mb-3">Proposal for {{ $project['title'] ?? '' }}</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Bid amount</label>
                    <input type="number" name="amount" class="form-control" value="{{ $proposal['amount'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estimated delivery (days)</label>
                    <input type="number" name="duration" class="form-control" value="{{ $proposal['duration'] ?? '' }}">
                </div>
            </div>
            <div class="mt-3">
                <label class="form-label">Cover letter</label>
                <textarea class="form-control" rows="5" name="cover_letter">{{ $proposal['cover_letter'] ?? '' }}</textarea>
            </div>
            <div class="mt-3">
                <label class="form-label">Attachments</label>
                <input type="file" class="form-control" multiple>
            </div>
            <div class="mt-3">
                <label class="form-label">Milestone breakdown (optional)</label>
                <div id="milestones"></div>
                <button class="btn btn-outline-secondary btn-sm" id="add-milestone">Add milestone</button>
            </div>
            <div class="mt-4 p-3 bg-light rounded">
                <h6 class="mb-1">Fee breakdown</h6>
                <p class="mb-0">Platform commission: <span id="commission">{{ $commission ?? '10%' }}</span></p>
                <p class="mb-0">Net earning: <span id="net-earning">$0</span></p>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <button class="btn btn-outline-secondary" id="save-draft">Save Draft</button>
            <button class="btn btn-primary" id="submit-proposal">Submit Proposal</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/proposalForm.js') }}"></script>
@endpush
