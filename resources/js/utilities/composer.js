const initGigvoraComposer = (root) => {
    const config = root.dataset.gvComposer ? JSON.parse(root.dataset.gvComposer) : {};
    const textarea = root.querySelector('#ChatmessageField');
    const attachmentInput = root.querySelector('#chatAttachmentInput');
    const toggles = root.querySelectorAll('[data-gv-composer-toggle]');
    const panels = root.querySelectorAll('[data-gv-composer-panel]');
    const gifEndpoint = root.dataset.gvGifEndpoint || config.gif?.endpoint || '';

    const closePanels = () => {
        panels.forEach((panel) => panel.classList.remove('is-active'));
    };

    const insertContent = (token) => {
        if (!textarea) {
            return;
        }

        const start = textarea.selectionStart ?? textarea.value.length;
        const end = textarea.selectionEnd ?? textarea.value.length;
        const before = textarea.value.slice(0, start);
        const after = textarea.value.slice(end);
        textarea.value = `${before}${token}${after}`;
        textarea.focus();
        const cursor = start + token.length;
        textarea.setSelectionRange(cursor, cursor);
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const target = toggle.dataset.gvComposerToggle;
            panels.forEach((panel) => {
                if (panel.dataset.gvComposerPanel === target) {
                    panel.classList.toggle('is-active');
                } else {
                    panel.classList.remove('is-active');
                }
            });
        });
    });

    document.addEventListener('click', (event) => {
        if (!root.contains(event.target)) {
            closePanels();
        }
    });

    root.querySelectorAll('[data-gv-insert]').forEach((button) => {
        button.addEventListener('click', () => {
            insertContent(button.dataset.gvInsert || '');
        });
    });

    root.querySelectorAll('[data-gv-attachment-trigger]').forEach((button) => {
        button.addEventListener('click', () => attachmentInput?.click());
    });

    const gifForm = root.querySelector('[data-gv-gif-search]');
    const gifResults = root.querySelector('[data-gv-gif-results]');

    if (gifForm && gifResults && gifEndpoint) {
        gifForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const input = gifForm.querySelector('input[type="search"]');
            const query = input?.value?.trim() || '';
            if (!query) {
                return;
            }

            gifResults.innerHTML = '<p class="gv-muted text-sm mb-0">Loading…</p>';

            try {
                const response = await fetch(`${gifEndpoint}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error('Failed to load GIFs');
                }

                const payload = await response.json();
                const data = payload.data || [];

                if (!data.length) {
                    gifResults.innerHTML = '<p class="gv-muted text-sm mb-0">No GIFs found.</p>';
                    return;
                }

                gifResults.innerHTML = '';
                data.forEach((gif) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'gv-composer-gif';
                    button.innerHTML = `<img src="${gif.preview || gif.url}" alt="${gif.title || 'GIF'}">`;
                    button.addEventListener('click', () => insertContent(` ${gif.url} `));
                    gifResults.appendChild(button);
                });
            } catch (error) {
                gifResults.innerHTML = '<p class="gv-muted text-sm mb-0">Unable to load GIFs.</p>';
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-gv-composer]').forEach(initGigvoraComposer);
});

