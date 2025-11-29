const sessionRoot = document.getElementById('webinar-live');
if (sessionRoot) {
    const attendeeCount = document.getElementById('attendee-count');
    const chatForm = document.getElementById('live-chat-form');
    const chatFeed = document.getElementById('live-chat-feed');
    const qaForm = document.getElementById('qa-form');
    const qaList = document.getElementById('qa-list');
    const notesSave = document.getElementById('notes-save');

    let attendees = 120;
    const updateAttendees = () => {
        attendees += Math.round(Math.random() * 4 - 2);
        attendees = Math.max(0, attendees);
        attendeeCount.textContent = `${attendees} attendees`;
    };
    setInterval(updateAttendees, 4000);

    chatForm?.addEventListener('submit', (event) => {
        event.preventDefault();
        const value = chatForm.message.value;
        if (!value) return;
        const node = document.createElement('div');
        node.className = 'mb-2';
        node.innerHTML = `<strong>You</strong> <small class="text-muted">now</small><div>${value}</div>`;
        chatFeed.appendChild(node);
        chatFeed.scrollTop = chatFeed.scrollHeight;
        chatForm.reset();
    });

    qaForm?.addEventListener('submit', (event) => {
        event.preventDefault();
        const value = qaForm.question.value;
        if (!value) return;
        const item = document.createElement('div');
        item.className = 'border rounded p-2 mb-2';
        item.textContent = value;
        qaList.appendChild(item);
        qaForm.reset();
    });

    document.querySelectorAll('[data-action]')?.forEach((button) => {
        button.addEventListener('click', () => {
            const action = button.dataset.action;
            const toast = document.createElement('div');
            toast.className = 'alert alert-info mt-2';
            toast.textContent = `${action} triggered`;
            button.closest('.card')?.appendChild(toast);
            setTimeout(() => toast.remove(), 1500);
        });
    });

    notesSave?.addEventListener('click', () => {
        const content = document.getElementById('notes-content')?.value ?? '';
        localStorage.setItem('webinar-notes', content);
        notesSave.textContent = 'Saved';
        setTimeout(() => (notesSave.textContent = 'Save Notes'), 1200);
    });
}
