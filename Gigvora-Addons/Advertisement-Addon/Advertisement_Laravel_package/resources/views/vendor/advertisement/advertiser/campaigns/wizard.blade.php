@extends('advertisement::layouts.app')

@section('title', ($campaign['id'] ?? false) ? 'Edit Campaign' : 'Create Campaign')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('advertisement.campaigns.index') }}">Campaigns</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ ($campaign['id'] ?? false) ? 'Edit' : 'Create' }}</li>
    </ol>
</nav>
@endsection

@section('ads-page')
<div class="gv-card space-y-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <p class="gv-label mb-1">{{ __('Campaign wizard') }}</p>
            <h4 class="gv-heading text-xl mb-0">{{ ($campaign['id'] ?? false) ? __('Edit campaign') : __('Create campaign') }}</h4>
        </div>
        <div class="gv-wizard-progress">
            <span>{{ __('Step') }} <strong id="wizard-step-label">1</strong> {{ __('of 5') }}</span>
            <div class="gv-progress">
                <span id="wizard-progress-bar" style="width:20%"></span>
            </div>
        </div>
    </div>
    <form id="campaign-wizard-form" method="POST" action="{{ $action ?? '' }}" class="space-y-5">
            @csrf
        <div class="gv-wizard-step" data-step="1">
            <h5 class="gv-heading text-lg mb-3">{{ __('Objective & naming') }}</h5>
            <div class="gv-grid-two">
                <div>
                    <label class="gv-label">{{ __('Campaign name') }}</label>
                    <input type="text" name="name" class="gv-input" value="{{ $campaign['name'] ?? '' }}" required>
                </div>
                <div>
                    <label class="gv-label">{{ __('Objective') }}</label>
                    <select name="objective" class="gv-input" required>
                        <option value="">{{ __('Select objective') }}</option>
                        <option value="traffic">Traffic</option>
                        <option value="conversions">Conversions</option>
                        <option value="awareness">Awareness</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="gv-wizard-step d-none" data-step="2">
            <h5 class="gv-heading text-lg mb-3">{{ __('Audience & targeting') }}</h5>
            <div class="gv-grid-three">
                <div>
                    <label class="gv-label">{{ __('Gender') }}</label>
                    <select name="gender[]" class="gv-input" multiple>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="nonbinary">Non-binary</option>
                    </select>
                </div>
                <div>
                    <label class="gv-label">{{ __('Locations') }}</label>
                    <input type="text" name="locations" class="gv-input" placeholder="{{ __('Country, Region, City') }}" />
                </div>
                <div>
                    <label class="gv-label">{{ __('Interests / tags') }}</label>
                    <input type="text" name="interests" class="gv-input" placeholder="{{ __('e.g. marketing, fitness') }}" />
                </div>
            </div>
            <div class="mt-3">
                <label class="gv-label">{{ __('Keywords') }}</label>
                <textarea name="keywords" class="gv-input" rows="2" placeholder="{{ __('Enter keywords separated by commas') }}"></textarea>
            </div>
        </div>

        <div class="gv-wizard-step d-none" data-step="3">
            <h5 class="gv-heading text-lg mb-3">{{ __('Placements') }}</h5>
            <div class="gv-placement-grid">
                @foreach(['newsfeed','newsfeed_inline','profile','search','jobs','freelance','story_interstitial','video_swipe'] as $placement)
                    <label class="gv-placement-card" for="placement-{{ $placement }}">
                        <input class="form-check-input" type="checkbox" name="placements[]" value="{{ $placement }}" id="placement-{{ $placement }}">
                        <span class="gv-placement-label">{{ \Illuminate\Support\Str::headline($placement) }}</span>
                        <small>{{ __('Creative guidance adapts per placement.') }}</small>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="gv-wizard-step d-none" data-step="4">
            <h5 class="gv-heading text-lg mb-3">{{ __('Budget & schedule') }}</h5>
            <div class="gv-grid-three">
                <div>
                    <label class="gv-label">{{ __('Budget type') }}</label>
                    <select name="budget_type" class="gv-input">
                        <option value="daily">{{ __('Daily') }}</option>
                        <option value="lifetime">{{ __('Lifetime') }}</option>
                    </select>
                </div>
                <div>
                    <label class="gv-label">{{ __('Amount') }}</label>
                    <input type="number" step="0.01" name="budget_amount" class="gv-input" />
                </div>
                <div>
                    <label class="gv-label">{{ __('Bidding model') }}</label>
                    <select name="bidding_model" class="gv-input">
                        <option value="cpc">CPC</option>
                        <option value="cpa">CPA</option>
                        <option value="cpm">CPM</option>
                    </select>
                </div>
            </div>
            <div class="gv-grid-two mt-3">
                <div>
                    <label class="gv-label">{{ __('Start date / time') }}</label>
                    <input type="datetime-local" name="start_at" class="gv-input" />
                </div>
                <div>
                    <label class="gv-label">{{ __('End date / time') }}</label>
                    <input type="datetime-local" name="end_at" class="gv-input" />
                </div>
            </div>
            <div class="gv-budget-preview" id="budget-preview">
                <div>
                    <h6 class="mb-1">{{ __('Estimated reach') }}</h6>
                    <p class="gv-muted mb-0" id="estimate-description">{{ __('Adjust budget to see forecast.') }}</p>
                </div>
                <div class="text-end">
                    <div class="gv-heading text-xl mb-0" id="estimate-impressions">--</div>
                    <small class="gv-muted">{{ __('Impressions') }}</small>
                </div>
                <div class="text-end">
                    <div class="gv-heading text-xl mb-0" id="estimate-clicks">--</div>
                    <small class="gv-muted">{{ __('Clicks') }}</small>
                </div>
            </div>
        </div>

        <div class="gv-wizard-step d-none" data-step="5">
            <h5 class="gv-heading text-lg mb-3">{{ __('Review & confirm') }}</h5>
            <div class="gv-grid-two" id="review-summary">
                <div class="gv-card">
                    <h6 class="gv-label text-uppercase mb-2">{{ __('Campaign') }}</h6>
                    <p class="mb-1"><strong>{{ __('Name:') }}</strong> <span data-summary="name"></span></p>
                    <p class="mb-1"><strong>{{ __('Objective:') }}</strong> <span data-summary="objective"></span></p>
                    <p class="mb-0"><strong>{{ __('Placements:') }}</strong> <span data-summary="placements"></span></p>
                </div>
                <div class="gv-card">
                    <h6 class="gv-label text-uppercase mb-2">{{ __('Budget') }}</h6>
                    <p class="mb-1"><strong>{{ __('Type:') }}</strong> <span data-summary="budget_type"></span></p>
                    <p class="mb-1"><strong>{{ __('Amount:') }}</strong> <span data-summary="budget_amount"></span></p>
                    <p class="mb-1"><strong>{{ __('Schedule:') }}</strong> <span data-summary="schedule"></span></p>
                    <p class="mb-0"><strong>{{ __('Bidding:') }}</strong> <span data-summary="bidding_model"></span></p>
                </div>
            </div>
            <div class="gv-alert gv-alert-warning mt-4">{{ __('Ensure all steps are valid before submitting. You can go back to adjust details.') }}</div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center gap-3 mt-4">
        <button class="gv-btn gv-btn-ghost" id="wizard-prev" disabled>{{ __('Back') }}</button>
        <div class="d-flex gap-3">
            <button class="gv-btn gv-btn-primary" id="wizard-next">{{ __('Next') }}</button>
            <button class="gv-btn gv-btn-primary d-none" id="wizard-submit">{{ ($campaign['id'] ?? false) ? __('Save changes') : __('Create campaign') }}</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/wizard.js') }}"></script>
@endpush
