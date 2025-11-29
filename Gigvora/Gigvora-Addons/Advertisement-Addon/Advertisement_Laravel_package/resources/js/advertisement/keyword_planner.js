import { get, post } from './apiClient';

const form = document.getElementById('keyword-planner-form');
const tableBody = document.querySelector('#keyword-results-table tbody');
const selectAll = document.getElementById('select-all-keywords');
const exportBtn = document.getElementById('export-keywords');
let debounceTimer;

const renderRows = (rows = []) => {
    tableBody.innerHTML = rows
        .map(
            (row) => `
        <tr>
            <td><input type="checkbox" class="keyword-select" value="${row.keyword}"></td>
            <td>${row.keyword}</td>
            <td>${row.cpc}</td>
            <td>${row.cpa}</td>
            <td>${row.impressions}</td>
        </tr>`
        )
        .join('');
};

const searchKeywords = async () => {
    const formData = new FormData(form);
    const params = Object.fromEntries(formData.entries());
    try {
        const { data } = await get('/advertisement/keyword-planner', params);
        renderRows(data.results || []);
    } catch (e) {
        console.error('Keyword planner failed', e);
    }
};

form?.addEventListener('submit', (e) => {
    e.preventDefault();
    searchKeywords();
});

document.getElementById('keyword-query')?.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(searchKeywords, 400);
});

selectAll?.addEventListener('change', () => {
    document.querySelectorAll('.keyword-select').forEach((cb) => {
        cb.checked = selectAll.checked;
    });
});

exportBtn?.addEventListener('click', async () => {
    const selected = Array.from(document.querySelectorAll('.keyword-select:checked')).map((cb) => cb.value);
    if (!selected.length) return alert('Select at least one keyword.');
    try {
        await post('/advertisement/keyword-planner/export', { keywords: selected });
        alert('Keywords exported to campaign');
    } catch (e) {
        alert('Failed to export keywords');
    }
});
