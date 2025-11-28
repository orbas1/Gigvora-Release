const registrationRoot = document.getElementById('webinar-detail');
const waitingRoomRoot = document.getElementById('webinar-waiting-room');

const http = async (url, options = {}) => {
    if (window.axios) {
        const method = (options.method || 'post').toLowerCase();
        const response = await window.axios({ url, method, data: options.body || options.data });
        return response.data;
    }
    const response = await fetch(url, {
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        method: options.method || 'POST',
        body: options.body || JSON.stringify(options.data || {}),
    });
    return response.json();
};

const formatCountdown = (ms) => {
    const totalSeconds = Math.max(0, Math.floor(ms / 1000));
    const h = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
    const m = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
    const s = String(totalSeconds % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
};

if (registrationRoot) {
    const registerBtn = registrationRoot.querySelector('[data-action="register"]');
    const joinBtn = registrationRoot.querySelector('[data-action="join"]');
    const alertEl = registrationRoot.querySelector('.alert-success');
    const countdownEl = document.getElementById('webinar-countdown');
    const startTime = Date.now() + 1000 * 60 * 45;

    registerBtn?.addEventListener('click', async () => {
        registerBtn.disabled = true;
        registerBtn.textContent = 'Registering...';
        try {
            const url = registrationRoot.dataset.registerUrl;
            if (url) {
                await http(url);
            }
            alertEl?.classList.remove('d-none');
            joinBtn?.classList.remove('d-none');
            registerBtn.classList.add('d-none');
        } catch (error) {
            registerBtn.disabled = false;
            registerBtn.textContent = 'Register';
            console.error('Registration failed', error);
        }
    });

    joinBtn?.addEventListener('click', () => {
        const waitingUrl = joinBtn.dataset.waitingUrl || registrationRoot.dataset.waitingUrl;
        if (waitingUrl) window.location.href = waitingUrl;
    });

    if (countdownEl) {
        setInterval(() => {
            const diff = startTime - Date.now();
            countdownEl.textContent = formatCountdown(diff);
        }, 1000);
    }
}

if (waitingRoomRoot) {
    const countdownEl = document.getElementById('waiting-countdown');
    const enterBtn = document.getElementById('enter-webinar');
    const announcements = document.getElementById('host-announcements');
    const startTime = Date.now() + 1000 * 60 * 5;

    const pollLiveStatus = () => {
        setTimeout(() => {
            announcements.textContent = 'Host has started the session.';
            enterBtn.disabled = false;
        }, 5000);
    };

    const updateCountdown = () => {
        const diff = startTime - Date.now();
        if (countdownEl) countdownEl.textContent = formatCountdown(diff);
        if (diff <= 0) {
            enterBtn.disabled = false;
        }
    };

    enterBtn?.addEventListener('click', () => {
        const url = waitingRoomRoot.dataset.liveUrl;
        if (url) window.location.href = url;
    });

    setInterval(updateCountdown, 1000);
    pollLiveStatus();
}
