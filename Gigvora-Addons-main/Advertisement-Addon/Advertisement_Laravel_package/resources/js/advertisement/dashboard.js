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
        const { data } = await get('/advertisement/dashboard', { range });
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
                row.addEventListener('click', () => {
                    window.location.href = `/advertisement/campaigns/${row.dataset.id}`;
                });
            });
        }
    } catch (e) {
        console.error('Failed loading dashboard', e);
    }
};

if (document.getElementById('ads-performance-chart')) {
    document.getElementById('refresh-dashboard')?.addEventListener('click', loadDashboard);
    document.getElementById('ads-date-range')?.addEventListener('change', loadDashboard);
    loadDashboard();
}
