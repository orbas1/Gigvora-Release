import { get, post } from './apiClient';

const filterForm = document.getElementById('campaign-filter-form');
const table = document.getElementById('campaigns-table');

const reloadTable = async () => {
    if (!filterForm || !table) return;
    const formData = new FormData(filterForm);
    const params = Object.fromEntries(formData.entries());
    try {
        const { data } = await get('/advertisement/campaigns', params);
        table.querySelector('tbody').innerHTML = data.rows;
        bindRowActions();
    } catch (e) {
        console.error('Could not load campaigns', e);
    }
};

const toggleCampaign = async (id) => {
    try {
        await post(`/advertisement/campaigns/${id}/toggle`);
        reloadTable();
    } catch (e) {
        alert('Failed to update campaign');
    }
};

const duplicateCampaign = async (id) => {
    try {
        await post(`/advertisement/campaigns/${id}/duplicate`);
        reloadTable();
    } catch (e) {
        alert('Failed to duplicate campaign');
    }
};

const archiveCampaign = async (id) => {
    if (!confirm('Archive this campaign?')) return;
    try {
        await post(`/advertisement/campaigns/${id}/archive`);
        reloadTable();
    } catch (e) {
        alert('Failed to archive campaign');
    }
};

const bindRowActions = () => {
    document.querySelectorAll('.campaign-pause').forEach((btn) =>
        btn.addEventListener('click', () => toggleCampaign(btn.closest('tr').dataset.id))
    );
    document.querySelectorAll('.campaign-duplicate').forEach((btn) =>
        btn.addEventListener('click', () => duplicateCampaign(btn.closest('tr').dataset.id))
    );
    document.querySelectorAll('.campaign-archive').forEach((btn) =>
        btn.addEventListener('click', () => archiveCampaign(btn.closest('tr').dataset.id))
    );
};

filterForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    reloadTable();
});

document.getElementById('campaign-filter-reset')?.addEventListener('click', () => reloadTable());

bindRowActions();
