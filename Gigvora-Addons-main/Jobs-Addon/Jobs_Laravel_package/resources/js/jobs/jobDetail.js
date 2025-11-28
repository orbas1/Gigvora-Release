const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const scrollToApply = () => {
    const form = document.querySelector('#job-apply-wizard') || document.querySelector('#application-form');
    if (form) form.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

const toggleSave = async (button) => {
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
        console.error('Save toggle failed', error);
        button.classList.toggle('active');
    }
};

const initJobDetail = () => {
    document.querySelector('#apply-now-btn')?.addEventListener('click', (e) => {
        e.preventDefault();
        const modal = document.querySelector('#applyModal');
        if (modal && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(modal).show();
        } else {
            scrollToApply();
        }
    });

    document.querySelectorAll('.save-job').forEach((button) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSave(button);
        });
    });
};

initJobDetail();
