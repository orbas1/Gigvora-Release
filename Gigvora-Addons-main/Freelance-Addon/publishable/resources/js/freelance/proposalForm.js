import { http, showToast } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('proposal-form');
    if (!root) return;
    const client = http();

    const updateNet = () => {
        const amount = Number(root.querySelector('[name="amount"]').value || 0);
        const commissionRate = 0.1;
        const net = amount - amount * commissionRate;
        root.querySelector('#net-earning').textContent = `$${net.toFixed(2)}`;
    };
    root.querySelector('[name="amount"]')?.addEventListener('input', updateNet);
    updateNet();

    const milestones = document.getElementById('milestones');
    document.getElementById('add-milestone')?.addEventListener('click', (e) => {
        e.preventDefault();
        const wrap = document.createElement('div');
        wrap.className = 'mb-2';
        wrap.innerHTML = '<input class="form-control mb-1" name="milestone_title[]" placeholder="Title"><input class="form-control" name="milestone_amount[]" placeholder="Amount">';
        milestones.appendChild(wrap);
    });

    const submit = (publish) => {
        const data = Object.fromEntries(new FormData(root).entries());
        data.publish = publish;
        client.post(root.dataset.saveUrl, data).then(() => showToast(publish ? 'Proposal submitted' : 'Draft saved'));
    };

    document.getElementById('save-draft')?.addEventListener('click', (e) => { e.preventDefault(); submit(false); });
    document.getElementById('submit-proposal')?.addEventListener('click', (e) => { e.preventDefault(); submit(true); });
});
