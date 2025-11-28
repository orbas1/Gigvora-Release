import { get, post } from './apiClient';

const filterForm = document.getElementById('campaign-filter-form');
const table = document.getElementById('campaigns-table');

const reloadTable = async () => {
    if (!filterForm || !table) return;
    const formData = new FormData(filterForm);
    const params = Object.fromEntries(formData.entries());
    try {
        const { data } = await get('/campaigns', params);
        const campaigns = data.data || [];
        table.querySelector('tbody').innerHTML = campaigns
            .map(
                (campaign) => `
                <tr data-id="${campaign.id}">
                    <td class="fw-semibold">${campaign.title}</td>
                    <td>${campaign.status ?? 'active'}</td>
                    <td>${campaign.objective ?? 'traffic'}</td>
                    <td>${campaign.start_date ?? '-'}</td>
                    <td>${campaign.end_date ?? '-'}</td>
                    <td><button class="btn btn-sm btn-outline-secondary campaign-toggle" data-status="${campaign.status}">Toggle</button></td>
                </tr>`
            )
            .join('');
        bindRowActions();
    } catch (e) {
        console.error('Could not load campaigns', e);
    }
};

const toggleCampaign = async (row) => {
    const id = row.dataset.id;
    const status = row.querySelector('.campaign-toggle')?.dataset.status === 'paused' ? 'active' : 'paused';
    try {
        await post(`/campaigns/${id}?_method=PUT`, { status });
        reloadTable();
    } catch (e) {
        alert('Failed to update campaign');
    }
};

const bindRowActions = () => {
    document.querySelectorAll('.campaign-toggle').forEach((btn) =>
        btn.addEventListener('click', (event) => toggleCampaign(event.currentTarget.closest('tr')))
    );
};

filterForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    reloadTable();
});

document.getElementById('campaign-filter-reset')?.addEventListener('click', () => reloadTable());

bindRowActions();
