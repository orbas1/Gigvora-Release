document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('admin-freelance-dashboard');
    if (!root) return;
    const volume = document.getElementById('volume-chart');
    const dispute = document.getElementById('dispute-chart');
    if (window.renderChart) {
        window.renderChart(volume, root.dataset.volume || []);
        window.renderChart(dispute, root.dataset.disputes || []);
    }
});
