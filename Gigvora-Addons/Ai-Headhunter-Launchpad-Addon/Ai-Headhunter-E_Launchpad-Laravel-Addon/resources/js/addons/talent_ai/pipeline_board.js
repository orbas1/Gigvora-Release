const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const notify = (message, type = 'info') => {
    const container = document.querySelector('[data-pipeline-feedback]');
    if (!container) {
        return;
    }
    container.textContent = message;
    container.className = `alert-muted ${type === 'error' ? 'text-danger' : 'text-success'}`;
};

const handleDrop = (card, stage, notes) => {
    const moveUrl = card.dataset.moveUrl;
    if (!moveUrl) {
        notify('Move URL missing', 'error');
        return;
    }

    fetch(moveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ stage, notes }),
    })
        .then(async (response) => {
            if (!response.ok) {
                const error = await response.json().catch(() => ({}));
                throw new Error(error.message || 'Unable to move candidate');
            }
            return response.json();
        })
        .then((data) => {
            notify(`Moved to ${stage}`, 'success');
            card.dataset.stage = stage;
            card.querySelector('[data-stage-label]')?.replaceChildren(document.createTextNode(stage));
            if (data.notes && card.querySelector('[data-note-preview]')) {
                card.querySelector('[data-note-preview]').textContent = data.notes;
            }
        })
        .catch((error) => notify(error.message, 'error'));
};

const initPipelineBoard = () => {
    document.querySelectorAll('.pipeline-board').forEach((board) => {
        board.querySelectorAll('.pipeline-card').forEach((card) => {
            card.setAttribute('draggable', 'true');
            card.addEventListener('dragstart', () => card.classList.add('dragging'));
            card.addEventListener('dragend', () => card.classList.remove('dragging'));
        });

        board.querySelectorAll('.pipeline-stage').forEach((stageColumn) => {
            stageColumn.addEventListener('dragover', (event) => {
                event.preventDefault();
                stageColumn.classList.add('ring');
            });
            stageColumn.addEventListener('dragleave', () => stageColumn.classList.remove('ring'));
            stageColumn.addEventListener('drop', (event) => {
                event.preventDefault();
                stageColumn.classList.remove('ring');
                const card = board.querySelector('.pipeline-card.dragging');
                if (!card) return;
                stageColumn.querySelector('.pipeline-items')?.appendChild(card);
                handleDrop(card, stageColumn.dataset.stage, card.dataset.notes || '');
            });
        });
    });
};

document.addEventListener('DOMContentLoaded', initPipelineBoard);
