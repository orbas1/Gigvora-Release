const initAdminSettings = () => {
    const settingsForm = document.querySelector('[data-admin-settings-form]');
    if (!settingsForm) return;

    const indicator = settingsForm.querySelector('[data-settings-feedback]');
    const toggleSummary = () => {
        const toggles = settingsForm.querySelectorAll('input[type="checkbox"]');
        const enabled = Array.from(toggles).filter((input) => input.checked).length;
        if (indicator) indicator.textContent = `${enabled} modules enabled`;
    };

    settingsForm.addEventListener('change', toggleSummary);
    toggleSummary();

    settingsForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const updateUrl = settingsForm.dataset.updateUrl;
        if (!updateUrl) return;
        const payload = Object.fromEntries(new FormData(settingsForm).entries());
        indicator.textContent = 'Saving...';
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
        })
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data.message || 'Unable to save settings');
                indicator.textContent = 'Settings saved';
            })
            .catch((error) => {
                indicator.textContent = error.message;
            });
    });
};

document.addEventListener('DOMContentLoaded', initAdminSettings);
