import { http, showToast } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('project-wizard');
    if (!root) return;
    const client = http();
    let step = 1;

    const panels = root.querySelectorAll('.project-panel');
    const tabs = root.querySelectorAll('#project-tabs .nav-link');
    const showStep = (s) => {
        step = s;
        panels.forEach(p => p.classList.toggle('d-none', Number(p.dataset.step) !== step));
        tabs.forEach(t => t.classList.toggle('active', Number(t.dataset.step) === step));
        root.querySelector('#project-step').textContent = step;
    };
    tabs.forEach(tab => tab.addEventListener('click', (e) => { e.preventDefault(); showStep(Number(tab.dataset.step)); }));
    document.getElementById('project-next')?.addEventListener('click', () => { if (step < 5) showStep(step + 1); });
    document.getElementById('project-prev')?.addEventListener('click', () => { if (step > 1) showStep(step - 1); });

    const questionContainer = root.querySelector('#questions');
    const addQuestion = () => {
        const block = document.createElement('div');
        block.className = 'mb-2';
        block.innerHTML = '<input type="text" class="form-control" name="questions[]" placeholder="Add screening question">';
        questionContainer.appendChild(block);
    };
    document.getElementById('add-question')?.addEventListener('click', (e) => { e.preventDefault(); addQuestion(); });

    const feeEstimate = () => {
        const amount = Number(root.querySelector('[name="budget"]').value || 0);
        root.querySelector('#fee-estimate').textContent = `$${(amount * 0.1).toFixed(2)}`;
        root.querySelector('#review-fee')?.textContent = `$${(amount * 0.1).toFixed(2)}`;
        root.querySelector('#review-budget')?.textContent = amount ? `$${amount}` : '';
        root.querySelector('#review-title')?.textContent = root.querySelector('[name="title"]').value;
    };
    root.querySelectorAll('input, textarea, select').forEach(el => el.addEventListener('input', feeEstimate));
    feeEstimate();

    const save = (publish = false) => {
        const data = Object.fromEntries(new FormData(root).entries());
        data.publish = publish;
        client.post(root.dataset.saveUrl, data).then(() => showToast(publish ? 'Project published' : 'Draft saved'));
    };

    document.getElementById('project-save')?.addEventListener('click', () => save(false));
    document.getElementById('save-project-draft')?.addEventListener('click', (e) => { e.preventDefault(); save(false); });
    document.getElementById('publish-project')?.addEventListener('click', (e) => { e.preventDefault(); save(true); });
});
