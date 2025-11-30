import { http, showToast } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('freelance-onboarding');
    if (!root) return;
    const client = http();

    function calculateCompleteness() {
        const required = ['skills', 'hourly_rate', 'timezone', 'bio'];
        const filled = required.filter(name => (root.querySelector(`[name="${name}"]`)?.value || '').trim().length > 0).length;
        const percent = Math.round((filled / required.length) * 100);
        root.querySelector('#profile-percent').textContent = `${percent}%`;
        const bar = root.querySelector('.progress-bar');
        if (bar) bar.style.width = `${percent}%`;
        return percent;
    }

    root.querySelectorAll('input, textarea').forEach(el => el.addEventListener('input', calculateCompleteness));
    calculateCompleteness();

    root.querySelectorAll('.role-toggle').forEach(toggle => {
        toggle.addEventListener('change', () => {
            client.post(root.dataset.fetchUrl, { role: toggle.dataset.role, enabled: toggle.checked });
        });
    });

    const saveBtn = document.getElementById('save-onboarding');
    if (saveBtn) {
        saveBtn.addEventListener('click', () => {
            const payload = Object.fromEntries(new FormData(root).entries());
            const percent = calculateCompleteness();
            if (percent < 50) {
                showToast('Please complete your basic profile first.', 'warning');
                return;
            }
            client.post(root.dataset.fetchUrl, payload).then(() => {
                showToast('Onboarding saved');
                window.location.href = '/freelance/freelancer/dashboard';
            });
        });
    }
});
