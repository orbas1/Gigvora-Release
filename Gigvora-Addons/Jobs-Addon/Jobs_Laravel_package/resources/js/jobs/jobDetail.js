const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const toggleSave = async (button) => {
    const jobId = button.dataset.jobId;
    const saved = button.classList.toggle('active');
    button.setAttribute('aria-pressed', saved ? 'true' : 'false');
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
        button.setAttribute('aria-pressed', button.classList.contains('active') ? 'true' : 'false');
    }
};

const initJobDetail = () => {
    document.querySelectorAll('.save-job').forEach((button) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSave(button);
        });
    });
};

initJobDetail();
