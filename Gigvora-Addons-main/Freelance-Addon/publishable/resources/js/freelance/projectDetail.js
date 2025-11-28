document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('toggle-description');
    if (!toggle) return;
    toggle.addEventListener('click', (e) => {
        e.preventDefault();
        const desc = toggle.previousElementSibling;
        if (desc) desc.classList.toggle('text-truncate');
    });
});
