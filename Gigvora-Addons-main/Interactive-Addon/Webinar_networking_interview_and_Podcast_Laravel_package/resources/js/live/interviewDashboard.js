const dashboardRoot = document.getElementById('interview-dashboard');
const candidateDetail = document.getElementById('candidate-interview');
const waitingRootInterview = document.getElementById('interview-waiting-room');
const liveRootInterview = document.getElementById('interview-live');

if (dashboardRoot) {
    const calendar = document.getElementById('calendar-widget');
    dashboardRoot.querySelectorAll('#upcoming-list .list-group-item').forEach((item) => {
        item.addEventListener('click', () => console.log('Open interview', item.querySelector('.fw-semibold')?.textContent));
    });
    if (calendar) {
        calendar.textContent = 'Calendar renders interview slots here';
    }
}

if (candidateDetail) {
    const joinWaiting = document.getElementById('join-waiting');
    const joinInterview = document.getElementById('join-interview');
    joinWaiting?.addEventListener('click', () => {
        joinWaiting.textContent = 'Opening waiting room...';
        const url = candidateDetail.dataset.waitingUrl;
        if (url) window.location.href = url;
    });
    setTimeout(() => joinInterview?.removeAttribute('disabled'), 5000);
}

if (waitingRootInterview) {
    const countdown = document.getElementById('interview-countdown');
    const enterBtn = document.getElementById('enter-interview');
    const start = Date.now() + 1000 * 60 * 2;
    setInterval(() => {
        const diff = start - Date.now();
        const minutes = String(Math.max(0, Math.floor(diff / 1000 / 60))).padStart(2, '0');
        const seconds = String(Math.max(0, Math.floor((diff / 1000) % 60))).padStart(2, '0');
        countdown.textContent = `${minutes}:${seconds}`;
        if (diff <= 0) enterBtn.disabled = false;
    }, 1000);
    enterBtn?.addEventListener('click', () => {
        const url = waitingRootInterview.dataset.liveUrl;
        if (url) window.location.href = url;
    });
}

if (liveRootInterview) {
    document.getElementById('toggle-mic')?.addEventListener('click', (e) => (e.target.textContent = 'Mic toggled'));
    document.getElementById('toggle-camera')?.addEventListener('click', (e) => (e.target.textContent = 'Camera toggled'));
    document.getElementById('leave-interview')?.addEventListener('click', () => (window.location.href = '/'));
    document.getElementById('notes-save')?.addEventListener('click', () => alert('Notes saved'));
}
