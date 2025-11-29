import { http } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('projects-browse');
    if (!root) return;
    const client = http();
    const searchBtn = document.getElementById('search-projects');
    const list = document.getElementById('projects-list');

    const reload = () => {
        const url = new URL(root.dataset.fetchUrl || window.location.href);
        root.querySelectorAll('select[name], input[name="q"]').forEach(el => url.searchParams.set(el.name, el.value));
        client.get(url.toString()).then((res) => {
            if (res?.content && list) list.innerHTML = res.content;
        });
    };
    searchBtn?.addEventListener('click', reload);
    root.querySelectorAll('select[name]').forEach(el => el.addEventListener('change', reload));
});
