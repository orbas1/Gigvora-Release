import { http, showToast } from './utils';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('gig-wizard');
    if (!root) return;
    const client = http();
    let step = 1;

    const panels = root.querySelectorAll('.wizard-panel');
    const tabs = root.querySelectorAll('#wizard-tabs .nav-link');

    const showStep = (s) => {
        step = s;
        panels.forEach(p => p.classList.toggle('d-none', Number(p.dataset.step) !== step));
        tabs.forEach(t => t.classList.toggle('active', Number(t.dataset.step) === step));
        root.querySelector('#wizard-step').textContent = step;
    };

    tabs.forEach(tab => tab.addEventListener('click', (e) => { e.preventDefault(); showStep(Number(tab.dataset.step)); }));
    document.getElementById('wizard-next')?.addEventListener('click', () => { if (step < 5) showStep(step + 1); });
    document.getElementById('wizard-prev')?.addEventListener('click', () => { if (step > 1) showStep(step - 1); });

    const faqList = document.getElementById('faq-list');
    const addFaq = () => {
        const wrapper = document.createElement('div');
        wrapper.className = 'mb-2 faq-item';
        wrapper.innerHTML = `<input class="form-control mb-2" name="faq_question[]" placeholder="Question"><textarea class="form-control" name="faq_answer[]" placeholder="Answer"></textarea>`;
        faqList.appendChild(wrapper);
    };
    document.getElementById('add-faq')?.addEventListener('click', (e) => { e.preventDefault(); addFaq(); });

    const reqList = document.getElementById('requirements-list');
    document.getElementById('add-requirement')?.addEventListener('click', (e) => {
        e.preventDefault();
        const item = document.createElement('div');
        item.className = 'mb-3 requirement-item';
        item.innerHTML = '<input type="text" class="form-control" name="requirements[]" placeholder="New requirement">';
        reqList.appendChild(item);
    });

    function calculateNet() {
        const commission = 0.1;
        root.querySelectorAll('.net-earning').forEach(span => {
            const input = root.querySelector(`[name="packages[${span.dataset.package}][price]"]`);
            const val = Number(input?.value || 0);
            span.textContent = `$${Math.max(val - val * commission, 0).toFixed(2)}`;
        });
    }
    root.querySelectorAll('input').forEach(el => el.addEventListener('input', calculateNet));
    calculateNet();

    const updatePreview = () => {
        root.querySelector('#preview-title').textContent = root.querySelector('[name="title"]').value;
        root.querySelector('#preview-description').textContent = root.querySelector('[name="description"]').value;
        const previewFaq = root.querySelector('#preview-faq');
        previewFaq.innerHTML = '';
        faqList.querySelectorAll('.faq-item').forEach(item => {
            const q = item.querySelector('input').value;
            const a = item.querySelector('textarea').value;
            if (q || a) {
                previewFaq.innerHTML += `<div class="mb-2"><strong>${q}</strong><div>${a}</div></div>`;
            }
        });
    };
    root.addEventListener('input', () => updatePreview());

    const save = (publish = false) => {
        const data = Object.fromEntries(new FormData(root).entries());
        data.publish = publish;
        client.post(root.dataset.saveUrl, data).then(() => {
            showToast(publish ? 'Gig published' : 'Draft saved');
        });
    };

    document.getElementById('wizard-save')?.addEventListener('click', () => save(false));
    document.getElementById('save-draft')?.addEventListener('click', (e) => { e.preventDefault(); save(false); });
    document.getElementById('publish-gig')?.addEventListener('click', (e) => { e.preventDefault(); save(true); });
});
