document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('gig-orders');
    if (!root) return;
    const dueBadges = root.querySelectorAll('tbody tr');
    dueBadges.forEach(row => {
        const dueCell = row.querySelector('td:nth-child(5)');
        if (dueCell) {
            const dateText = dueCell.textContent;
            // placeholder countdown hook
            dueCell.dataset.countdown = dateText;
        }
    });
    root.querySelectorAll('.status-filter').forEach(btn => btn.addEventListener('click', () => {
        root.querySelectorAll('.status-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }));
});
