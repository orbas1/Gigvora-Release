const catalogue = document.getElementById('networking-catalogue');
const detail = document.getElementById('networking-detail');
const waitingRoot = document.getElementById('networking-waiting-room');

const httpPost = async (url, body = {}) => {
    if (!url) return;
    if (window.axios) {
        await window.axios.post(url, body);
        return;
    }
    await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(body),
    });
};

if (catalogue) {
    const filterSelects = catalogue.querySelectorAll('select');
    filterSelects.forEach((select) => select.addEventListener('change', () => console.log('Filter changed', select.value)));

    setInterval(() => {
        const liveCards = catalogue.querySelectorAll('.badge.bg-danger');
        liveCards.forEach((badge) => badge.classList.toggle('pulse'));
    }, 6000);
}

if (detail) {
    const registerBtn = detail.querySelector('[data-action="register"]');
    const waitlistBtn = detail.querySelector('[data-action="waitlist"]');
    const cardForm = document.getElementById('card-form');

    registerBtn?.addEventListener('click', async () => {
        registerBtn.disabled = true;
        registerBtn.textContent = 'Registering...';
        try {
            await httpPost(detail.dataset.registerUrl);
            registerBtn.textContent = 'Registered';
        } catch (error) {
            console.error(error);
            registerBtn.textContent = 'Register';
            registerBtn.disabled = false;
        }
    });

    waitlistBtn?.addEventListener('click', () => alert('Added to waitlist'));

    document.getElementById('edit-card')?.addEventListener('click', () => {
        const form = document.getElementById('edit-card-form');
        if (form) form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });

    cardForm?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const payload = Object.fromEntries(new FormData(cardForm).entries());
        try {
            await httpPost(detail.dataset.registerUrl, payload);
            detail.querySelector('#business-card').innerHTML = `
                <div class="fw-semibold">${payload.name || 'Your Name'}</div>
                <div class="text-muted">${payload.headline || ''}</div>
                <div>${payload.company || ''}</div>
            `;
        } catch (error) {
            console.error('Failed to save card', error);
        }
    });
}

if (waitingRoot) {
    const countdownEl = document.getElementById('networking-countdown');
    const joinBtn = document.getElementById('join-networking');
    const startTime = Date.now() + 1000 * 60 * 3;

    setInterval(() => {
        const diff = startTime - Date.now();
        const minutes = String(Math.max(0, Math.floor(diff / 1000 / 60))).padStart(2, '0');
        const seconds = String(Math.max(0, Math.floor((diff / 1000) % 60))).padStart(2, '0');
        countdownEl.textContent = `${minutes}:${seconds}`;
        if (diff <= 0) joinBtn.disabled = false;
    }, 1000);

    document.getElementById('waiting-card-form')?.addEventListener('submit', (event) => {
        event.preventDefault();
        joinBtn.disabled = false;
    });
}
