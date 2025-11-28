import { post } from './apiClient';

const steps = Array.from(document.querySelectorAll('.wizard-step'));
const nextBtn = document.getElementById('wizard-next');
const prevBtn = document.getElementById('wizard-prev');
const submitBtn = document.getElementById('wizard-submit');
const form = document.getElementById('campaign-wizard-form');
const stepLabel = document.getElementById('wizard-step-label');
let currentStep = 0;

const updateSummary = () => {
    const formData = new FormData(form);
    const summary = (key) => formData.getAll(key).filter(Boolean).join(', ');
    document.querySelector('[data-summary="name"]')?.replaceChildren(document.createTextNode(formData.get('name') || ''));
    document.querySelector('[data-summary="objective"]')?.replaceChildren(document.createTextNode(formData.get('objective') || ''));
    document.querySelector('[data-summary="placements"]')?.replaceChildren(document.createTextNode(summary('placements[]')));
    document.querySelector('[data-summary="budget_type"]')?.replaceChildren(document.createTextNode(formData.get('budget_type') || ''));
    document.querySelector('[data-summary="budget_amount"]')?.replaceChildren(document.createTextNode(formData.get('budget_amount') || ''));
    const schedule = `${formData.get('start_at') || '-'} - ${formData.get('end_at') || '-'}`;
    document.querySelector('[data-summary="schedule"]')?.replaceChildren(document.createTextNode(schedule));
    document.querySelector('[data-summary="bidding_model"]')?.replaceChildren(document.createTextNode(formData.get('bidding_model') || ''));
};

const showStep = (index) => {
    steps.forEach((step, i) => step.classList.toggle('d-none', i !== index));
    currentStep = index;
    stepLabel && (stepLabel.textContent = String(index + 1));
    prevBtn.disabled = index === 0;
    nextBtn.classList.toggle('d-none', index === steps.length - 1);
    submitBtn.classList.toggle('d-none', index !== steps.length - 1);
    if (index === steps.length - 1) updateSummary();
};

const validateStep = (index) => {
    const step = steps[index];
    if (!step) return true;
    const required = step.querySelectorAll('[required]');
    for (const input of required) {
        if (!input.value) {
            input.classList.add('is-invalid');
            input.addEventListener('input', () => input.classList.remove('is-invalid'), { once: true });
            return false;
        }
    }
    return true;
};

nextBtn?.addEventListener('click', () => {
    if (!validateStep(currentStep)) return;
    if (currentStep < steps.length - 1) showStep(currentStep + 1);
});

prevBtn?.addEventListener('click', () => {
    if (currentStep > 0) showStep(currentStep - 1);
});

submitBtn?.addEventListener('click', async () => {
    if (!validateStep(currentStep)) return;
    const formData = new FormData(form);
    try {
        submitBtn.disabled = true;
        await post(form.action, formData);
        window.location.href = '/advertisement/campaigns';
    } catch (e) {
        alert('Unable to save campaign');
    } finally {
        submitBtn.disabled = false;
    }
});

const budgetSlider = document.querySelector('input[name="budget_amount"]');
const estimateImpressions = document.getElementById('estimate-impressions');
const estimateClicks = document.getElementById('estimate-clicks');

const updateEstimates = () => {
    if (!budgetSlider || !estimateImpressions) return;
    const amount = parseFloat(budgetSlider.value || '0');
    estimateImpressions.textContent = Math.round(amount * 120).toLocaleString();
    estimateClicks.textContent = Math.round(amount * 4).toLocaleString();
};

budgetSlider?.addEventListener('input', updateEstimates);

showStep(0);
