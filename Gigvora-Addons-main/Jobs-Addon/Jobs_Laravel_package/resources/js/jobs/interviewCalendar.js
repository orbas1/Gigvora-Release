const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const initCalendar = () => {
    const calendarEl = document.querySelector('#interview-calendar');
    if (!calendarEl) return;
    const events = JSON.parse(calendarEl.dataset.events || '[]');
    calendarEl.innerHTML = '';
    events.forEach((event) => {
        const badge = document.createElement('div');
        badge.className = 'border rounded p-2 mb-2';
        badge.innerHTML = `<strong>${event.title}</strong><div class="text-muted small">${event.date}</div>`;
        calendarEl.appendChild(badge);
    });
};

const bindScheduling = () => {
    document.querySelector('#new-slot')?.addEventListener('click', () => {
        const date = prompt('Pick a date (YYYY-MM-DD)');
        if (!date) return;
        fetch('/employer/interviews', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
            },
            body: JSON.stringify({ date }),
        }).then(() => window.location.reload());
    });

    document.querySelectorAll('.reschedule').forEach((btn) => {
        btn.addEventListener('click', () => {
            const date = prompt('New date/time');
            if (!date) return;
            fetch(`/employer/interviews/${btn.dataset.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({ date }),
            }).then(() => window.location.reload());
        });
    });

    document.querySelectorAll('.cancel').forEach((btn) => {
        btn.addEventListener('click', () => {
            fetch(`/employer/interviews/${btn.dataset.id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken || '' },
            }).then(() => window.location.reload());
        });
    });
};

initCalendar();
bindScheduling();
