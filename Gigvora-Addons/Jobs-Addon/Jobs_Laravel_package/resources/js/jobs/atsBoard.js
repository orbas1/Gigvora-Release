const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const handleDragAndDrop = () => {
    document.querySelectorAll('.stage-dropzone').forEach((zone) => {
        zone.addEventListener('dragover', (e) => e.preventDefault());
        zone.addEventListener('drop', async (e) => {
            e.preventDefault();
            const candidateId = e.dataTransfer.getData('candidate');
            const card = document.querySelector(`.candidate-card[data-candidate-id="${candidateId}"]`);
            if (card) {
                zone.prepend(card);
                await updateStage(candidateId, zone.dataset.stage);
            }
        });
    });

    document.querySelectorAll('.candidate-card').forEach((card) => {
        card.setAttribute('draggable', true);
        card.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('candidate', card.dataset.candidateId);
            card.classList.add('opacity-50');
        });
        card.addEventListener('dragend', () => card.classList.remove('opacity-50'));
    });
};

const updateStage = async (candidateId, stage) => {
    try {
        await fetch(`/employer/candidates/${candidateId}/stage`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
            body: JSON.stringify({ stage }),
        });
        toast('Stage updated');
    } catch (error) {
        toast('Unable to update stage', true);
    }
};

const toast = (message, isError = false) => {
    const el = document.createElement('div');
    el.className = `toast align-items-center text-bg-${isError ? 'danger' : 'success'} show position-fixed top-0 end-0 m-3`;
    el.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 2500);
};

const initFilters = () => {
    document.querySelector('#candidate-search')?.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.candidate-card').forEach((card) => {
            const text = card.innerText.toLowerCase();
            card.classList.toggle('d-none', !text.includes(term));
        });
    });
};

handleDragAndDrop();
initFilters();
