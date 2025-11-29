import { http, showToast } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const centre = document.getElementById('dispute-centre') || document.getElementById('dispute-detail');
    if (!centre) return;
    const client = http();

    centre.querySelectorAll('#dispute-tabs .nav-link')?.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            centre.querySelectorAll('#dispute-tabs .nav-link').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    const form = document.getElementById('dispute-message-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(form).entries());
            client.post(`/disputes/${centre.dataset.disputeId}/message`, data).then(() => showToast('Statement submitted'));
        });
    }
});
