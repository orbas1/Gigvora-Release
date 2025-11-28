import { get } from './apiClient';

const budget = document.getElementById('forecast-budget');
const duration = document.getElementById('forecast-duration');
const budgetLabel = document.getElementById('forecast-budget-label');
const durationLabel = document.getElementById('forecast-duration-label');
const chartEl = document.getElementById('forecast-chart');
const applyBtn = document.getElementById('apply-forecast');
let chartInstance;
let lastForecast = null;

const updateLabels = () => {
    budgetLabel && (budgetLabel.textContent = `$${budget?.value || 0}`);
    durationLabel && (durationLabel.textContent = duration?.value || 0);
};

const renderChart = (labels = [], dataset = []) => {
    if (!chartEl || typeof Chart === 'undefined') return;
    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(chartEl.getContext('2d'), {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Projected Conversions', data: dataset, backgroundColor: '#4e73df' }] },
        options: { responsive: true, maintainAspectRatio: false },
    });
};

const updateMetrics = (metrics) => {
    document.getElementById('forecast-impressions')?.replaceChildren(document.createTextNode(metrics.impressions || '--'));
    document.getElementById('forecast-clicks')?.replaceChildren(document.createTextNode(metrics.clicks || '--'));
    document.getElementById('forecast-conversions')?.replaceChildren(document.createTextNode(metrics.conversions || '--'));
    document.getElementById('forecast-cost')?.replaceChildren(document.createTextNode(metrics.cost || '--'));
};

const fetchForecast = async () => {
    try {
        const { data } = await get('/advertisement/forecast', {
            budget: budget?.value,
            duration: duration?.value,
            campaign_id: document.querySelector('select[name="campaign_id"]')?.value,
        });
        lastForecast = data;
        updateMetrics(data.metrics || {});
        renderChart(data.labels || [], data.conversions || []);
        applyBtn?.removeAttribute('disabled');
    } catch (e) {
        console.error('Forecast failed', e);
        applyBtn?.setAttribute('disabled', 'disabled');
    }
};

budget?.addEventListener('input', () => {
    updateLabels();
    fetchForecast();
});

duration?.addEventListener('input', () => {
    updateLabels();
    fetchForecast();
});

document.getElementById('forecast-form')?.addEventListener('submit', (e) => {
    e.preventDefault();
    fetchForecast();
});

updateLabels();
if (chartEl) fetchForecast();

applyBtn?.addEventListener('click', () => {
    if (!lastForecast) return;

    const event = new CustomEvent('advertisement:forecastApplied', {
        detail: lastForecast,
    });

    document.dispatchEvent(event);
});
