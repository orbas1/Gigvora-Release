const steps = Array.from(document.querySelectorAll('#job-post-wizard .wizard-step'));
const progress = document.querySelector('#job-wizard-progress');
const nextBtn = document.querySelector('#job-next-step');
const prevBtn = document.querySelector('#job-prev-step');
const form = document.querySelector('#job-wizard-form');
let currentStep = 0;

const setStep = (index) => {
    steps.forEach((step, i) => step.classList.toggle('hidden', i !== index));
    if (progress) {
        const pct = ((index + 1) / steps.length) * 100;
        progress.style.width = `${pct}%`;
    }
    prevBtn?.toggleAttribute('disabled', index === 0);
    if (nextBtn) nextBtn.textContent = index === steps.length - 1 ? 'Publish' : 'Next';
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

const initPostWizard = () => {
    if (!form || steps.length === 0) {
        return;
    }
    setStep(currentStep);

    nextBtn?.addEventListener('click', () => {
        if (!validateStep(currentStep)) {
            return;
        }

        if (currentStep === steps.length - 1) {
            form.submit();
            return;
        }

        currentStep = Math.min(currentStep + 1, steps.length - 1);
        setStep(currentStep);
    });

    prevBtn?.addEventListener('click', () => {
        currentStep = Math.max(currentStep - 1, 0);
        setStep(currentStep);
    });
};

initPostWizard();

