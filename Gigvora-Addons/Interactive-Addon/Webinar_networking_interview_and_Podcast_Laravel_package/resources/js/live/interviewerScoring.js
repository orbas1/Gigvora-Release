const panel = document.getElementById('interviewer-panel');
if (panel) {
    const table = document.getElementById('scoring-table');
    const saveBtn = document.getElementById('save-scores');
    const lockBtn = document.getElementById('lock-scores');
    const recommendation = document.getElementById('recommendation');
    const comments = document.getElementById('panel-comments');
    const status = panel.querySelector('[data-status]');
    const saveUrl = panel.dataset.saveUrl;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    const setStatus = (message, tone = 'muted') => {
        if (!status) return;
        status.textContent = message;
        status.classList.remove('text-[var(--gv-color-danger)]', 'text-[var(--gv-color-success)]', 'text-[var(--gv-color-neutral-600)]');
        if (tone === 'error') status.classList.add('text-[var(--gv-color-danger)]');
        else if (tone === 'success') status.classList.add('text-[var(--gv-color-success)]');
        else status.classList.add('text-[var(--gv-color-neutral-600)]');
    };

    const serializeScores = () => {
        const rows = table.querySelectorAll('tbody tr');
        return Array.from(rows).reduce(
            (acc, row) => {
                const key = row.dataset.key || row.dataset.name || 'criterion';
                acc.criteria[key] = row.dataset.name || key;
                acc.scores[key] = Number(row.querySelector('[name="score"]').value);
                acc.comments[key] = row.querySelector('[name="comment"]').value;
                return acc;
            },
            { criteria: {}, scores: {}, comments: {} },
        );
    };

    const persist = async () => {
        if (!saveUrl || !csrf) {
            setStatus('Missing save endpoint or CSRF token.', 'error');
            return;
        }

        const { criteria, scores, comments: perCriteriaComments } = serializeScores();
        const formData = new FormData();
        Object.entries(criteria).forEach(([key, value]) => formData.append(`criteria[${key}]`, value));
        Object.entries(scores).forEach(([key, value]) => formData.append(`scores[${key}]`, value));
        const overallComments = comments?.value?.trim();
        if (overallComments) formData.append('comments', overallComments);
        if (Object.keys(perCriteriaComments).length) {
            formData.append('criteria_comments', JSON.stringify(perCriteriaComments));
        }

        formData.append('recommendation', recommendation?.value ?? '');

        setStatus('Saving...', 'muted');
        saveBtn?.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    Accept: 'application/json',
                },
                body: formData,
            });

            if (!response.ok) {
                throw new Error(`Save failed (${response.status})`);
            }

            setStatus('Scores saved and synced.', 'success');
            saveBtn.textContent = 'Saved';
            setTimeout(() => (saveBtn.textContent = 'Save'), 1500);
        } catch (error) {
            setStatus(error.message, 'error');
        } finally {
            saveBtn?.removeAttribute('aria-busy');
        }
    };

    table?.addEventListener('change', persist);
    recommendation?.addEventListener('change', persist);
    comments?.addEventListener('change', persist);

    saveBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        persist();
    });

    lockBtn?.addEventListener('click', () => {
        table.querySelectorAll('select, input, textarea').forEach((el) => (el.disabled = true));
        lockBtn.disabled = true;
        lockBtn.textContent = 'Locked';
    });
}
