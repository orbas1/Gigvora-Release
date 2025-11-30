import { http } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('admin-fees');
    if (!root) return;
    const client = http();
    const example = document.getElementById('example-amount');
    const preview = document.getElementById('fee-preview');

    example?.addEventListener('input', () => {
        const amount = Number(example.value || 0);
        if (!amount) {
            preview.textContent = 'Enter amount to preview';
            return;
        }
        if (root.dataset.previewUrl) {
            client.post(root.dataset.previewUrl, { amount }).then(res => {
                preview.textContent = `Fee: ${res.fee} · Net: ${res.net}`;
            });
        } else {
            const fee = amount * 0.1;
            preview.textContent = `Fee: $${fee.toFixed(2)} · Net: $${(amount - fee).toFixed(2)}`;
        }
    });

    document.getElementById('save-fees')?.addEventListener('click', () => {
        const data = Object.fromEntries(new FormData(root).entries());
        client.post(root.dataset.previewUrl || '/admin/fees', data).then(() => alert('Settings saved'));
    });
});
