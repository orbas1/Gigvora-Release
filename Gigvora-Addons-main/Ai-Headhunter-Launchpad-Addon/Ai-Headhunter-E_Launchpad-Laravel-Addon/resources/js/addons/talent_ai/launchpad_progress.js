const calculateProgress = (container) => {
    const tasks = container.querySelectorAll('.launchpad-task-checkbox');
    if (!tasks.length) return 0;
    const completed = Array.from(tasks).filter((task) => task.checked).length;
    return Math.round((completed / tasks.length) * 100);
};

const updateProgress = (container) => {
    const progress = calculateProgress(container);
    const bar = container.querySelector('[data-progress-bar]');
    const label = container.querySelector('[data-progress-label]');
    if (bar) bar.style.width = `${progress}%`;
    if (label) label.textContent = `${progress}% completed`;
};

const pushUpdate = (checkbox) => {
    const updateUrl = checkbox.dataset.updateUrl;
    if (!updateUrl) return;
    fetch(updateUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ task_id: checkbox.value, completed: checkbox.checked }),
    }).catch(() => {});
};

const initLaunchpadProgress = () => {
    document.querySelectorAll('[data-launchpad-progress]').forEach((container) => {
        updateProgress(container);
        container.querySelectorAll('.launchpad-task-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                updateProgress(container);
                pushUpdate(checkbox);
            });
        });
    });
};

document.addEventListener('DOMContentLoaded', initLaunchpadProgress);
