import { http, showToast } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('client-project-detail');
    if (!root) return;
    const client = http();
    const tabs = root.querySelectorAll('#project-tabs .nav-link');
    tabs.forEach(tab => tab.addEventListener('click', (e) => {
        e.preventDefault();
        const target = tab.dataset.tab;
        document.querySelectorAll('[id^="tab-"]').forEach(section => section.classList.add('d-none'));
        document.getElementById(`tab-${target}`).classList.remove('d-none');
        tabs.forEach(t => t.classList.toggle('active', t === tab));
    }));

    root.querySelectorAll('#proposal-cards [data-action]')?.forEach(btn => {
        btn.addEventListener('click', () => {
            const action = btn.dataset.action;
            const card = btn.closest('[data-proposal-id]');
            client.post(`/projects/${root.dataset.projectId}/proposals/${card.dataset.proposalId}/${action}`, {})
                .then(() => showToast(`Proposal ${action}`));
        });
    });
});
