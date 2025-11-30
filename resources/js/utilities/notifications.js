const initNotificationCenter = () => {
    const statTargets = document.querySelectorAll('[data-gv-notification-stat]');
    const rows = document.querySelectorAll('[data-gv-notification-row]');
    const filters = document.querySelectorAll('[data-gv-notification-filter]');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    const updateStat = (key, value) => {
        statTargets.forEach((stat) => {
            if (stat.dataset.gvNotificationStat === key) {
                stat.textContent = new Intl.NumberFormat().format(value);
            }
        });
    };

    const applyFilter = (type) => {
        rows.forEach((row) => {
            const matches = type === 'all' || row.dataset.notificationType === type;
            row.style.display = matches ? '' : 'none';
        });
    };

    filters.forEach((filter) => {
        filter.addEventListener('click', () => {
            filters.forEach((btn) => btn.classList.remove('gv-notification-filter--active'));
            filter.classList.add('gv-notification-filter--active');
            const type = filter.dataset.gvNotificationFilterType || 'all';
            applyFilter(type);
        });
    });

    document.querySelectorAll('[data-gv-notification-dismiss]').forEach((button) => {
        button.addEventListener('click', async () => {
            if (button.disabled) {
                return;
            }

            const url = button.dataset.gvNotificationDismiss;
            const row = button.closest('[data-gv-notification-row]');

            if (!url || !csrf || !row) {
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Request failed');
                }

                const payload = await response.json();
                row.dataset.notificationState = 'read';
                row.classList.remove('gv-notification-row--unread');
                button.remove();

                if (typeof payload.unread !== 'undefined') {
                    updateStat('unread', payload.unread);
                }
            } catch (error) {
                console.error(error);
                button.disabled = false;
            }
        });
    });
};

document.addEventListener('DOMContentLoaded', initNotificationCenter);

