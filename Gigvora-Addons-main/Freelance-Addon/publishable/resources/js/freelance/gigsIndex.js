import { http } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('gig-list');
    if (!root) return;
    const client = http();
    root.querySelectorAll('select[name], input[name="search"]').forEach(el => el.addEventListener('change', () => reload()));
    const reload = () => {
        const url = new URL(root.dataset.fetchUrl || window.location.href);
        root.querySelectorAll('select[name], input[name="search"]').forEach(el => url.searchParams.set(el.name, el.value));
        client.get(url.toString()).then(html => {
            const container = document.getElementById('gig-list-container');
            if (container && html?.content) container.innerHTML = html.content;
        });
    };
});
