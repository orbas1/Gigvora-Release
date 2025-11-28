const rangeSelect = document.querySelector('#dashboard-range');

const buildChart = (ctxId, data) => {
    const ctx = document.getElementById(ctxId);
    if (!ctx || typeof Chart === 'undefined') return null;
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{ label: 'Applications', data: data.values, borderColor: '#0d6efd', tension: 0.3 }],
        },
        options: { plugins: { legend: { display: false } } },
    });
};

let chartInstance;
const fetchStats = async () => {
    const range = rangeSelect?.value || '7';
    try {
        const response = await fetch(`/employer/dashboard/stats?range=${range}`);
        if (!response.ok) return;
        const payload = await response.json();
        chartInstance?.destroy();
        chartInstance = buildChart('applications-chart', payload.applications || { labels: [], values: [] });
    } catch (error) {
        console.error('Unable to load stats', error);
    }
};

const initDashboard = () => {
    fetchStats();
    rangeSelect?.addEventListener('change', fetchStats);
};

initDashboard();
