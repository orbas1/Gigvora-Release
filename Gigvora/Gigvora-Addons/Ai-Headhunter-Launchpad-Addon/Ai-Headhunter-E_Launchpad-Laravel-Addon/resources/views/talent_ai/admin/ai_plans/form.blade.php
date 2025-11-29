<div class="talent-ai-card">
    <h3 class="h6 mb-3">Create or update plan</h3>
    <form method="post" action="{{ route('addons.talent_ai.admin.plans.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input class="form-control" name="name" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug</label>
                <input class="form-control" name="slug" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Price</label>
                <input class="form-control" name="price" type="number" step="0.01">
            </div>
            <div class="col-12">
                <label class="form-label">Limits (JSON)</label>
                <textarea class="form-control" name="limits" rows="3" placeholder='{"daily": 50, "monthly": 500}'></textarea>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">@lang('talent_ai::addons_talent_ai.common.save')</button>
        </div>
    </form>
</div>
