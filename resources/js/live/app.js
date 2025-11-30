document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('[data-live-nav-link]');

    navLinks.forEach((link) => {
        link.addEventListener('keydown', (event) => {
            if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') {
                return;
            }

            event.preventDefault();

            const items = Array.from(navLinks);
            const currentIndex = items.indexOf(link);
            if (currentIndex === -1) {
                return;
            }

            let nextIndex = currentIndex;
            if (event.key === 'ArrowDown') {
                nextIndex = (currentIndex + 1) % items.length;
            } else if (event.key === 'ArrowUp') {
                nextIndex = (currentIndex - 1 + items.length) % items.length;
            }

            items[nextIndex]?.focus();
        });
    });
});

