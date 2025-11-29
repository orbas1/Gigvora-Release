const initVolunteeringFilters = () => {
    const filterBar = document.querySelector('[data-volunteering-filters]');
    if (!filterBar) return;

    const applyFilters = () => {
        const keyword = filterBar.querySelector('input[name="keyword"]')?.value.toLowerCase() || '';
        const sector = filterBar.querySelector('select[name="sector"]')?.value || '';
        const status = filterBar.querySelector('select[name="status"]')?.value || '';

        document.querySelectorAll('[data-volunteering-card]').forEach((card) => {
            const text = card.textContent.toLowerCase();
            const matchesKeyword = !keyword || text.includes(keyword);
            const matchesSector = !sector || card.dataset.sector === sector;
            const matchesStatus = !status || card.dataset.status === status;
            card.style.display = matchesKeyword && matchesSector && matchesStatus ? '' : 'none';
        });
    };

    filterBar.querySelectorAll('input, select').forEach((input) => {
        input.addEventListener('change', applyFilters);
        input.addEventListener('keyup', applyFilters);
    });
};

document.addEventListener('DOMContentLoaded', initVolunteeringFilters);
