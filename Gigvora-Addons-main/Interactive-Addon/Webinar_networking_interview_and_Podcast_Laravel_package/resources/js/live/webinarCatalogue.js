const http = async (url, options = {}) => {
    if (window.axios) {
        const method = (options.method || 'get').toLowerCase();
        const response = await window.axios({ url, method, data: options.body || options.data });
        return response.data;
    }
    const response = await fetch(url, { headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, ...options });
    return response.json();
};

const catalogueEl = document.getElementById('webinar-catalogue');
if (catalogueEl) {
    const cardsContainer = document.getElementById('webinar-cards');
    const paginationBtn = document.querySelector('#webinar-pagination button');

    const applyFilters = async () => {
        const formData = new FormData(catalogueEl.querySelector('.card-body'));
        const params = new URLSearchParams(formData);
        const activeFilter = catalogueEl.querySelector('.btn-group .active')?.dataset.filter;
        if (activeFilter) params.set('scope', activeFilter);
        const url = catalogueEl.dataset.fetchUrl;
        if (!url) return;
        try {
            const data = await http(url + '?' + params.toString());
            if (Array.isArray(data?.data)) {
                cardsContainer.innerHTML = data.data
                    .map((item) => `
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-1">${item.title}</h5>
                                    <p class="text-muted small">${item.host} • ${item.datetime}</p>
                                    <p class="text-muted">${item.description ?? ''}</p>
                                    <div class="d-flex align-items-center mt-auto">
                                        <span class="badge bg-${item.status === 'Live' ? 'danger' : 'secondary'} me-2">${item.status}</span>
                                        <a class="btn btn-sm btn-primary ms-auto" href="${item.href ?? '#'}">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `)
                    .join('');
            }
        } catch (error) {
            console.error('Failed to load webinars', error);
        }
    };

    catalogueEl.querySelectorAll('[name], .btn-group button').forEach((el) => {
        el.addEventListener('input', applyFilters);
        el.addEventListener('click', (event) => {
            catalogueEl.querySelectorAll('.btn-group button').forEach((btn) => btn.classList.remove('active'));
            event.target.classList.add('active');
            applyFilters();
        });
    });

    paginationBtn?.addEventListener('click', () => {
        paginationBtn.disabled = true;
        paginationBtn.textContent = 'Loading...';
        setTimeout(() => {
            paginationBtn.disabled = false;
            paginationBtn.textContent = 'Load more';
        }, 800);
    });

    const refreshLiveBadges = () => {
        cardsContainer.querySelectorAll('.badge.bg-danger').forEach((badge) => {
            badge.classList.toggle('pulse');
        });
    };
    setInterval(refreshLiveBadges, 5000);
}
