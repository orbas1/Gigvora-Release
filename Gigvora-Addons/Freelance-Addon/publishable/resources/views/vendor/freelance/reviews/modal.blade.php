<div class="modal" tabindex="-1" id="review-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="review-form">
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="d-flex gap-1" id="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="fs-4" data-value="{{ $i }}">☆</span>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Headline</label>
                        <input type="text" class="form-control" name="headline" placeholder="Great collaboration">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comment</label>
                        <textarea class="form-control" rows="4" name="comment"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-review">Submit Review</button>
            </div>
        </div>
    </div>
</div>
