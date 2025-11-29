import { http, showToast } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('order-detail');
    if (!root) return;
    const client = http();
    const messageForm = document.getElementById('message-form');
    messageForm?.addEventListener('submit', (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(messageForm).entries());
        client.post(`/orders/${root.dataset.orderId}/messages`, data).then(() => showToast('Message sent'));
    });

    document.getElementById('deliver-work')?.addEventListener('click', () => {
        client.post(`/orders/${root.dataset.orderId}/deliver`, {}).then(() => showToast('Delivery submitted'));
    });
});
