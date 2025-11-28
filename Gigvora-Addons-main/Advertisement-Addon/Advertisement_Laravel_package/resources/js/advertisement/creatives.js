import { get, post } from './apiClient';

const previewContainer = document.getElementById('creative-preview');
const typeSelect = document.getElementById('creative-type');
const headlineInput = document.querySelector('input[name="headline"]');
const descriptionInput = document.querySelector('textarea[name="description"]');
const urlInput = document.querySelector('input[name="url"]');
const mediaSection = document.getElementById('media-section');
const previewTypeBadge = document.getElementById('preview-type');

const renderPreview = () => {
    if (!previewContainer) return;
    const headline = headlineInput?.value || 'Your headline';
    const description = descriptionInput?.value || 'Description will appear here.';
    const url = urlInput?.value || '#';
    const type = typeSelect?.value || 'text';
    previewTypeBadge && (previewTypeBadge.textContent = type.toUpperCase());
    previewContainer.innerHTML = `
        <div class="mb-2 text-muted small">Preview (${type})</div>
        <h6 class="mb-1">${headline}</h6>
        <p class="text-muted mb-1">${description}</p>
        <a href="${url}" class="fw-semibold">${url}</a>
    `;
};

[typeSelect, headlineInput, descriptionInput, urlInput].forEach((input) => {
    input?.addEventListener('input', renderPreview);
});

const toggleMediaSection = () => {
    if (!typeSelect || !mediaSection) return;
    const needsMedia = ['banner', 'video', 'recommendation'].includes(typeSelect.value);
    mediaSection.classList.toggle('d-none', !needsMedia);
};

typeSelect?.addEventListener('change', () => {
    toggleMediaSection();
    renderPreview();
});

toggleMediaSection();
renderPreview();

// Preview modal for list page
const previewModal = document.getElementById('creativePreviewModal');
if (previewModal) {
    document.querySelectorAll('.creative-preview').forEach((btn) =>
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const body = document.getElementById('creative-preview-body');
            if (!body) return;
            body.innerHTML = '<p class="text-muted">Loading preview...</p>';
            try {
                const { data } = await get(`/advertisement/creatives/${id}`);
                body.innerHTML = `<h6>${data.headline}</h6><p>${data.description}</p><a href="${data.url}">${data.url}</a>`;
            } catch (e) {
                body.innerHTML = '<p class="text-danger">Unable to load preview.</p>';
            }
            const modal = new bootstrap.Modal(previewModal);
            modal.show();
        })
    );
}

// Inline actions
const pauseButtons = document.querySelectorAll('.creative-toggle');
pauseButtons.forEach((btn) =>
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        try {
            await post(`/advertisement/creatives/${id}/toggle`);
            window.location.reload();
        } catch (e) {
            alert('Could not update creative');
        }
    })
);

const archiveButtons = document.querySelectorAll('.creative-archive');
archiveButtons.forEach((btn) =>
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        if (!confirm('Archive this creative?')) return;
        try {
            await post(`/advertisement/creatives/${id}/archive`);
            window.location.reload();
        } catch (e) {
            alert('Could not archive creative');
        }
    })
);

const draftButton = document.getElementById('save-draft');
draftButton?.addEventListener('click', () => {
    const form = document.getElementById('creative-form');
    const draftInput = document.createElement('input');
    draftInput.type = 'hidden';
    draftInput.name = 'status';
    draftInput.value = 'draft';
    form.appendChild(draftInput);
    form.submit();
});
