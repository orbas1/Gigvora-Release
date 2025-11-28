const runAiTool = (form) => {
    const button = form.querySelector('button[type="submit"]');
    const output = form.closest('.ai-tool-card')?.querySelector('[data-ai-output]');
    const endpoint = form.dataset.endpoint;

    if (!endpoint || !output) return;

    const payload = Object.fromEntries(new FormData(form).entries());
    button?.setAttribute('disabled', 'disabled');
    button?.classList.add('opacity-60');
    output.innerHTML = '<p class="text-muted">Running AI...</p>';

    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
    })
        .then(async (response) => {
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Unable to run AI');
            return data;
        })
        .then((data) => {
            const drafts = Array.isArray(data.variants) ? data.variants : [data.result || 'No output'];
            output.innerHTML = drafts
                .map((draft, index) => `<div class="talent-ai-card"><strong>Version ${index + 1}</strong><p>${draft}</p></div>`)
                .join('');
        })
        .catch((error) => {
            output.innerHTML = `<p class="text-danger">${error.message}</p>`;
        })
        .finally(() => {
            button?.removeAttribute('disabled');
            button?.classList.remove('opacity-60');
        });
};

const initAiWorkspace = () => {
    document.querySelectorAll('.ai-tool-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            runAiTool(form);
        });
    });
};

document.addEventListener('DOMContentLoaded', initAiWorkspace);
