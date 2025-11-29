import { http } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('freelancer-dashboard');
    if (!root) return;
    const client = http();
    const chartEl = document.getElementById('earnings-chart');
    if (chartEl && window.renderChart) {
        window.renderChart(chartEl, root.dataset.chartData || []);
    }

    const recommended = document.getElementById('recommended-projects');
    function loadRecommendations() {
        if (!recommended?.dataset.fetchUrl) return;
        recommended.innerHTML = '<p class="text-muted">Loading...</p>';
        client.get(recommended.dataset.fetchUrl).then((projects = []) => {
            recommended.innerHTML = projects.map(p => `<div class="mb-3"><strong>${p.title}</strong><div class="text-muted small">${p.budget}</div></div>`).join('');
        });
    }
    document.getElementById('refresh-recommended')?.addEventListener('click', loadRecommendations);
    loadRecommendations();
});
