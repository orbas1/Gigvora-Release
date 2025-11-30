const steps = Array.from(document.querySelectorAll('#job-apply-wizard .step'));
const progress = document.querySelector('#apply-progress');
const nextBtn = document.querySelector('#next-step');
const prevBtn = document.querySelector('#prev-step');
let currentStep = 0;

const setStep = (index) => {
    steps.forEach((step, i) => step.classList.toggle('hidden', i !== index));
    if (progress) {
        const pct = ((index + 1) / steps.length) * 100;
        progress.style.width = `${pct}%`;
    }
    prevBtn?.toggleAttribute('disabled', index === 0);
    if (nextBtn) nextBtn.textContent = index === steps.length - 1 ? 'Submit' : 'Next';
};

const validateStep = (index) => {
    const fields = steps[index]?.querySelectorAll('[required]') || [];
    let valid = true;
    fields.forEach((field) => {
        if (!field.value) {
            valid = false;
            field.classList.add('border-red-500', 'ring-1', 'ring-red-500/40');
        } else {
            field.classList.remove('border-red-500', 'ring-1', 'ring-red-500/40');
        }
    });
    return valid;
};

const submitApplication = async (form) => {
    const data = new FormData(form);
    try {
        const response = await fetch(form.action, {
            method: form.method || 'POST',
            body: data,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (!response.ok) throw new Error('Failed to submit');
        const toast = document.createElement('div');
        toast.className = 'alert alert-success mt-3';
        toast.textContent = 'Application submitted successfully!';
        form.after(toast);
    } catch (error) {
        alert('Unable to submit application. Please try again.');
    }
};

const initWizard = () => {
    const form = document.querySelector('#application-form');
    if (!form || steps.length === 0) return;
    setStep(currentStep);

    nextBtn?.addEventListener('click', async (event) => {
        event.preventDefault();
        if (!validateStep(currentStep)) return;
        if (currentStep === steps.length - 1) {
            await submitApplication(form);
            return;
        }
        currentStep = Math.min(currentStep + 1, steps.length - 1);
        setStep(currentStep);
    });

    prevBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        currentStep = Math.max(currentStep - 1, 0);
        setStep(currentStep);
    });

    document.querySelector('#generate-cover-letter')?.addEventListener('click', (e) => {
        e.preventDefault();
        const textarea = form.querySelector('textarea[name="cover_letter"]');
        if (textarea) {
            textarea.value = 'Dear hiring team, I am excited to apply for this role...';
        }
    });
};

initWizard();
