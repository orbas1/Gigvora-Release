const debounce = (fn, delay = 300) => {
    let t;
    return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), delay);
    };
};

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const updateJobs = async (url, params = null) => {
    const target = document.querySelector('#job-results-list');
    if (!target) return;
    const query = params ? `?${new URLSearchParams(params).toString()}` : '';
    const response = await fetch(url + query, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    if (response.ok) {
        const html = await response.text();
        target.innerHTML = html;
    }
};

const initSearchPage = () => {
    const form = document.querySelector('#job-search-form');
    const filters = document.querySelector('#jobs-search-app');
    if (!form) return;

    const performSearch = debounce(async () => {
        const data = new FormData(form);
        const params = {};
        data.forEach((value, key) => (params[key] = value));
        document.querySelectorAll('.filter-input:checked').forEach((input) => {
            params['types[]'] = params['types[]'] || [];
            params['types[]'].push(input.value);
        });
        params.salary = document.querySelector('#salary-range')?.value;
        params.experience = document.querySelector('#experience-filter')?.value;
        params.industry = document.querySelector('#industry-filter')?.value;
        params.posted = document.querySelector('#posted-date-filter')?.value;
        await updateJobs(form.getAttribute('action') || window.location.pathname, params);
    }, 350);

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        performSearch();
    });

    filters?.addEventListener('change', (event) => {
        if (event.target.matches('input, select')) {
            performSearch();
        }
    });

    document.querySelector('#apply-filters')?.addEventListener('click', (e) => {
        e.preventDefault();
        performSearch();
    });

    document.body.addEventListener('click', async (event) => {
        const button = event.target.closest('.save-job');
        if (!button) return;
        event.preventDefault();
        const jobId = button.dataset.jobId;
        const saved = button.classList.toggle('active');
        try {
            await fetch(`/jobs/${jobId}/save`, {
                method: saved ? 'POST' : 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({ saved }),
            });
        } catch (error) {
            console.error('Unable to toggle save', error);
            button.classList.toggle('active');
        }
    });
};

initSearchPage();
export default initSearchPage;
