const dashboardRoot = document.getElementById('interview-dashboard');
const candidateDetail = document.getElementById('candidate-interview');
const waitingRootInterview = document.getElementById('interview-waiting-room');
const liveRootInterview = document.getElementById('interview-live');

const formatTimeLeft = (diff) => {
    const minutes = String(Math.max(0, Math.floor(diff / 1000 / 60))).padStart(2, '0');
    const seconds = String(Math.max(0, Math.floor((diff / 1000) % 60))).padStart(2, '0');
    return `${minutes}:${seconds}`;
};

const updateButtonState = (button, enabled) => {
    if (!button) return;
    button.classList.toggle('opacity-50', !enabled);
    button.classList.toggle('pointer-events-none', !enabled);
    button.toggleAttribute('aria-disabled', !enabled);
    button.disabled = !enabled;
};

if (dashboardRoot) {
    const calendar = document.getElementById('calendar-widget');
    if (calendar) {
        const start = calendar.dataset.startAt ? new Date(calendar.dataset.startAt) : null;
        if (start) {
            calendar.textContent = `${start.toDateString()} • ${start.toLocaleTimeString()}`;
        }
    }
}

if (candidateDetail) {
    const joinWaiting = document.getElementById('join-waiting');
    const joinInterview = document.getElementById('join-interview');
    const waitingUrl = candidateDetail.dataset.waitingUrl;
    const liveUrl = candidateDetail.dataset.liveUrl;
    const start = candidateDetail.dataset.startAt ? new Date(candidateDetail.dataset.startAt) : null;

    const refreshJoinState = () => {
        if (!start || !joinInterview) return;
        const diff = start - new Date();
        const ready = diff <= 0;
        updateButtonState(joinInterview, ready);
        joinInterview.textContent = ready ? joinInterview.dataset.readyLabel || joinInterview.textContent : formatTimeLeft(diff);
    };

    joinWaiting?.addEventListener('click', () => {
        if (!waitingUrl) return;
        joinWaiting.textContent = 'Opening waiting room...';
        window.location.href = waitingUrl;
    });

    joinInterview?.addEventListener('click', () => {
        if (!liveUrl) return;
        window.location.href = liveUrl;
    });

    refreshJoinState();
    if (start) setInterval(refreshJoinState, 1000);
}

if (waitingRootInterview) {
    const countdown = document.getElementById('interview-countdown');
    const enterBtn = document.getElementById('enter-interview');
    const start = waitingRootInterview.dataset.startAt ? new Date(waitingRootInterview.dataset.startAt) : null;
    const liveUrl = waitingRootInterview.dataset.liveUrl;
    const waitingStatus = document.querySelector('[data-waiting-status]');

    const tick = () => {
        if (!start || !countdown || !enterBtn) return;
        const diff = start - Date.now();
        countdown.textContent = formatTimeLeft(diff);
        const ready = diff <= 0;
        updateButtonState(enterBtn, ready);
        if (ready && waitingStatus) {
            waitingStatus.textContent = waitingStatus.dataset.liveLabel || 'Live now';
            waitingStatus.classList.add('gv-pill--danger');
        }
    };

    tick();
    if (start) setInterval(tick, 1000);

    enterBtn?.addEventListener('click', () => {
        if (liveUrl) window.location.href = liveUrl;
    });
}

if (liveRootInterview) {
    const status = liveRootInterview.querySelector('[data-live-status]');
    const start = liveRootInterview.dataset.startAt ? new Date(liveRootInterview.dataset.startAt) : null;
    const updateLiveStatus = () => {
        if (!status || !start) return;
        const diff = start - new Date();
        status.textContent = diff <= 0 ? status.dataset.liveLabel || 'Live' : formatTimeLeft(diff);
    };

    updateLiveStatus();
    if (start) setInterval(updateLiveStatus, 1000);

    document.getElementById('toggle-mic')?.addEventListener('click', (e) => (e.target.textContent = 'Mic toggled'));
    document.getElementById('toggle-camera')?.addEventListener('click', (e) => (e.target.textContent = 'Camera toggled'));
    document.getElementById('leave-interview')?.addEventListener('click', () => (window.location.href = '/'));
    document.getElementById('notes-save')?.addEventListener('click', () => alert('Notes saved'));
}
