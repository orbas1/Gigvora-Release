const buildChart = (id, label, data, color) => {
    const ctx = document.getElementById(id);
    if (!ctx || typeof Chart === 'undefined') return;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{ label, data: data.values, backgroundColor: color }],
        },
        options: { plugins: { legend: { display: false } } },
    });
};

const loadAdminMetrics = async () => {
    try {
        const response = await fetch('/admin/jobs/stats');
        if (!response.ok) return;
        const payload = await response.json();
        buildChart('jobs-posted-chart', 'Jobs', payload.jobs || { labels: [], values: [] }, '#0d6efd');
        buildChart('applications-chart-admin', 'Applications', payload.applications || { labels: [], values: [] }, '#6f42c1');
    } catch (error) {
        console.error('Unable to load admin metrics', error);
    }
};

loadAdminMetrics();
