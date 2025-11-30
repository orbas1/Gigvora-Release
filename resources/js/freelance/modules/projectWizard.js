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
    const nextButton = document.getElementById('project-next');
    if (nextButton) {
        nextButton.addEventListener('click', () => { if (step < 5) showStep(step + 1); });
    }
    const prevButton = document.getElementById('project-prev');
    if (prevButton) {
        prevButton.addEventListener('click', () => { if (step > 1) showStep(step - 1); });
    }

    const questionContainer = root.querySelector('#questions');
    const addQuestion = () => {
        const block = document.createElement('div');
        block.className = 'mb-2';
        block.innerHTML = '<input type="text" class="form-control" name="questions[]" placeholder="Add screening question">';
        questionContainer.appendChild(block);
    };
    const addQuestionBtn = document.getElementById('add-question');
    if (addQuestionBtn) {
        addQuestionBtn.addEventListener('click', (e) => { e.preventDefault(); addQuestion(); });
    }

    const feeEstimate = () => {
        const amount = Number(root.querySelector('[name="budget"]').value || 0);
        root.querySelector('#fee-estimate').textContent = `$${(amount * 0.1).toFixed(2)}`;
        const reviewFee = root.querySelector('#review-fee');
        if (reviewFee) {
            reviewFee.textContent = `$${(amount * 0.1).toFixed(2)}`;
        }
        const reviewBudget = root.querySelector('#review-budget');
        if (reviewBudget) {
            reviewBudget.textContent = amount ? `$${amount}` : '';
        }
        const reviewTitle = root.querySelector('#review-title');
        if (reviewTitle) {
            reviewTitle.textContent = root.querySelector('[name="title"]').value;
        }
    };
    root.querySelectorAll('input, textarea, select').forEach(el => el.addEventListener('input', feeEstimate));
    feeEstimate();

    const save = (publish = false) => {
        const data = Object.fromEntries(new FormData(root).entries());
        data.publish = publish;
        client.post(root.dataset.saveUrl, data).then(() => showToast(publish ? 'Project published' : 'Draft saved'));
    };

    const saveButton = document.getElementById('project-save');
    if (saveButton) {
        saveButton.addEventListener('click', () => save(false));
    }
    const saveDraftButton = document.getElementById('save-project-draft');
    if (saveDraftButton) {
        saveDraftButton.addEventListener('click', (e) => { e.preventDefault(); save(false); });
    }
    const publishButton = document.getElementById('publish-project');
    if (publishButton) {
        publishButton.addEventListener('click', (e) => { e.preventDefault(); save(true); });
    }
});
