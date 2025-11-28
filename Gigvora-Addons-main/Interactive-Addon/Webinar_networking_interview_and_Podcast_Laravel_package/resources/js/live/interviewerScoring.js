const panel = document.getElementById('interviewer-panel');
if (panel) {
    const table = document.getElementById('scoring-table');
    const saveBtn = document.getElementById('save-scores');
    const lockBtn = document.getElementById('lock-scores');
    const recommendation = document.getElementById('recommendation');

    const serializeScores = () => {
        const rows = table.querySelectorAll('tbody tr');
        return Array.from(rows).map((row) => ({
            criteria: row.dataset.name,
            score: row.querySelector('[name="score"]').value,
            comment: row.querySelector('[name="comment"]').value,
        }));
    };

    const persist = () => {
        const payload = { criteria: serializeScores(), recommendation: recommendation?.value };
        localStorage.setItem('interviewer-scores', JSON.stringify(payload));
    };

    table?.addEventListener('change', persist);
    recommendation?.addEventListener('change', persist);

    saveBtn?.addEventListener('click', () => {
        persist();
        saveBtn.textContent = 'Saved';
        setTimeout(() => (saveBtn.textContent = 'Save'), 1000);
    });

    lockBtn?.addEventListener('click', () => {
        table.querySelectorAll('select, input').forEach((el) => (el.disabled = true));
        lockBtn.disabled = true;
        lockBtn.textContent = 'Locked';
    });
}
