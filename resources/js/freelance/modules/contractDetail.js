import { http, showToast } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('contract-detail') || document.getElementById('client-contract-detail');
    if (!root) return;
    const client = http();

    root.querySelectorAll('[data-action]')?.forEach(btn => {
        btn.addEventListener('click', () => {
            const action = btn.dataset.action;
            client.post(`/contracts/${root.dataset.contractId}/${action}`, {}).then(() => showToast(`Action ${action} triggered`));
        });
    });

    const timeline = root.querySelector('.timeline');
    if (timeline) {
        timeline.querySelectorAll('.timeline-marker').forEach(marker => {
            marker.classList.add('animate-pulse');
        });
    }

    const messageForm = root.querySelector('#contract-message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(messageForm).entries());
            client.post(`/contracts/${root.dataset.contractId}/messages`, data).then(() => showToast('Message sent'));
        });
    }
});
