import { get } from './apiClient';

const chartEl = document.getElementById('ads-performance-chart');
let chartInstance;

const renderChart = (labels = [], datasets = []) => {
    if (!chartEl || typeof Chart === 'undefined') return;
    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(chartEl.getContext('2d'), {
        type: 'line',
        data: { labels, datasets },
        options: { responsive: true, maintainAspectRatio: false },
    });
};

const loadDashboard = async () => {
    const range = document.getElementById('ads-date-range')?.value;
    try {
        const { data } = await get('/dashboard', { range });
        (data.kpis || []).forEach((kpi) => {
            const el = document.getElementById(`kpi-${kpi.id}`);
            if (el) el.innerText = kpi.value;
        });
        renderChart(data.labels || [], data.series || []);
        const tableBody = document.getElementById('top-campaigns-body');
        if (tableBody && data.topCampaigns) {
            tableBody.innerHTML = data.topCampaigns
                .map(
                    (c) => `
                <tr data-id="${c.id}" class="campaign-row">
                    <td class="fw-semibold">${c.name}</td>
                    <td><span class="badge bg-success">${c.status}</span></td>
                    <td>${c.impressions}</td>
                    <td>${c.clicks}</td>
                    <td>${c.ctr}</td>
                    <td>${c.spend}</td>
                </tr>`
                )
                .join('');
            document.querySelectorAll('.campaign-row').forEach((row) => {
                row.addEventListener('click', (event) => showCampaign(event.currentTarget.dataset.id));
            });
        }
    } catch (e) {
        console.error('Failed loading dashboard', e);
        const feedback = document.getElementById('ads-dashboard-feedback');
        if (feedback) {
            feedback.textContent = 'Unable to load dashboard data. Please try again.';
            feedback.classList.remove('d-none');
        }
    }
};

const showCampaign = async (id) => {
    const modalEl = document.getElementById('campaignDetailModal');
    const body = document.getElementById('campaign-detail-body');
    if (!modalEl || !body || !id) return;

    body.innerHTML = '<p class="text-muted">Loading campaign...</p>';

    try {
        const { data } = await get(`/campaigns/${id}`);
        body.innerHTML = `
            <h6 class="fw-semibold mb-2">${data.title}</h6>
            <dl class="row mb-0">
                <dt class="col-sm-4">Status</dt>
                <dd class="col-sm-8">${data.status ?? 'Active'}</dd>
                <dt class="col-sm-4">Budget</dt>
                <dd class="col-sm-8">$${Number(data.budget ?? 0).toLocaleString()}</dd>
                <dt class="col-sm-4">Objective</dt>
                <dd class="col-sm-8">${data.objective ?? 'N/A'}</dd>
            </dl>
        `;
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    } catch (error) {
        body.innerHTML = `<p class="text-danger">Unable to load campaign details.</p>`;
    }
};

if (document.getElementById('ads-performance-chart')) {
    document.getElementById('refresh-dashboard')?.addEventListener('click', loadDashboard);
    document.getElementById('ads-date-range')?.addEventListener('change', loadDashboard);
    loadDashboard();
}
