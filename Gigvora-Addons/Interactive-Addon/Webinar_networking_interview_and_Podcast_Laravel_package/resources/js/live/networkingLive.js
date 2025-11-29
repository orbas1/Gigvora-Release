const liveRoot = document.getElementById('networking-live');
if (liveRoot) {
    const timerEl = document.getElementById('round-timer');
    const roundIndicator = document.getElementById('round-indicator');
    const partnerName = document.getElementById('partner-name');
    const partnerRole = document.getElementById('partner-role');
    const notes = document.getElementById('partner-notes');
    const nextUp = document.getElementById('next-up');

    let round = 1;
    let remaining = 120;

    const renderTimer = () => {
        const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
        const seconds = String(remaining % 60).padStart(2, '0');
        timerEl.textContent = `${minutes}:${seconds}`;
    };

    const rotatePartner = () => {
        round += 1;
        remaining = 120;
        partnerName.textContent = `Partner ${round}`;
        partnerRole.textContent = 'New connection';
        roundIndicator.textContent = `Round ${round} of 6`;
        nextUp.textContent = 'Rotating... new pairing assigned.';
        setTimeout(() => (nextUp.textContent = 'Next pairing will appear automatically.'), 1500);
    };

    setInterval(() => {
        remaining -= 1;
        if (remaining <= 0) {
            rotatePartner();
        }
        renderTimer();
    }, 1000);

    document.getElementById('skip-round')?.addEventListener('click', rotatePartner);
    document.getElementById('report-partner')?.addEventListener('click', () => alert('Report submitted'));
    document.getElementById('leave-session')?.addEventListener('click', () => (window.location.href = '/'));

    notes?.addEventListener('change', () => localStorage.setItem('networking-notes', notes.value));
}
