import { get, post } from './apiClient';

// Moderation actions
const approve = async (id) => {
    await post(`/advertisement/admin/moderation/${id}/approve`);
};
const reject = async (id, reason = '') => {
    await post(`/advertisement/admin/moderation/${id}/reject`, { reason });
};

const bindModeration = () => {
    document.querySelectorAll('.moderation-approve').forEach((btn) =>
        btn.addEventListener('click', async () => {
            await approve(btn.closest('tr').dataset.id);
            window.location.reload();
        })
    );
    document.querySelectorAll('.moderation-reject').forEach((btn) =>
        btn.addEventListener('click', async () => {
            const reason = prompt('Reason for rejection?') || '';
            await reject(btn.closest('tr').dataset.id, reason);
            window.location.reload();
        })
    );
    document.querySelectorAll('.moderation-preview').forEach((btn) =>
        btn.addEventListener('click', async () => {
            const id = btn.closest('tr').dataset.id;
            const modalEl = document.getElementById('moderationPreviewModal');
            const body = document.getElementById('moderation-preview-body');
            body.innerHTML = '<p class="text-muted">Loading...</p>';
            const { data } = await get(`/advertisement/admin/moderation/${id}`);
            body.innerHTML = `<h6>${data.headline}</h6><p>${data.description}</p>`;
            new bootstrap.Modal(modalEl).show();
            document.getElementById('modal-approve')?.addEventListener('click', async () => {
                await approve(id);
                window.location.reload();
            });
            document.getElementById('modal-reject')?.addEventListener('click', async () => {
                const reason = prompt('Reason for rejection?') || '';
                await reject(id, reason);
                window.location.reload();
            });
        })
    );
};

// Settings save
const bindSettings = () => {
    document.querySelectorAll('.settings-section').forEach((form) =>
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(form).entries());
            try {
                await post('/advertisement/admin/settings', { section: form.dataset.section, ...data });
                if (window.toast) {
                    window.toast.success('Settings saved');
                } else {
                    alert('Settings saved');
                }
            } catch (err) {
                alert('Failed to save settings');
            }
        })
    );
};

// Keyword pricing inline edit
const bindKeywordPricing = () => {
    document.querySelectorAll('#keyword-pricing-table .editable').forEach((cell) => {
        cell.addEventListener('blur', async () => {
            const row = cell.closest('tr');
            const payload = {
                keyword_id: row.dataset.id,
                field: cell.dataset.field,
                value: cell.textContent,
            };
            try {
                await post('/advertisement/admin/keyword-pricing', payload);
            } catch (err) {
                cell.classList.add('text-danger');
            }
        });
    });
};

const bindAdvertisers = () => {
    document.querySelectorAll('.suspend-advertiser').forEach((btn) =>
        btn.addEventListener('click', async () => {
            await post(`/advertisement/admin/advertisers/${btn.closest('tr').dataset.id}/suspend`);
            window.location.reload();
        })
    );
    document.querySelectorAll('.flag-advertiser').forEach((btn) =>
        btn.addEventListener('click', async () => {
            await post(`/advertisement/admin/advertisers/${btn.closest('tr').dataset.id}/flag`);
            window.location.reload();
        })
    );
};

bindModeration();
bindSettings();
bindKeywordPricing();
bindAdvertisers();
