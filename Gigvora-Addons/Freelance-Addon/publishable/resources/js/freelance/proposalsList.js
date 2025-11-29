import { http } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('proposals-list');
    if (!root) return;
    const client = http();
    const tabs = root.querySelectorAll('#proposal-tabs .nav-link');
    tabs.forEach(tab => tab.addEventListener('click', (e) => {
        e.preventDefault();
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const status = tab.dataset.status;
        client.get(`/freelance/proposals?status=${status}`).then(res => {
            if (res?.content) document.getElementById('proposals-body').innerHTML = res.content;
        });
    }));
});
