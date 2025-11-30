const bubbleSelector = '[data-gv-utilities-bubble]';

const fetchCollection = async (url, params = {}) => {
    if (!url) {
        return [];
    }

    const query = new URLSearchParams({ per_page: '5', ...(params ?? {}) });
    const response = await fetch(`${url}?${query}`, {
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (!response.ok) {
        const message = await response.text();
        throw new Error(message || `Request failed (${response.status})`);
    }

    const payload = await response.json();

    if (Array.isArray(payload)) {
        return payload;
    }

    return payload.data ?? [];
};

const fetchQuickTools = async (endpoint, context) => {
    if (!endpoint) {
        return null;
    }

    const query = new URLSearchParams({ context: context || 'global' });
    const response = await fetch(`${endpoint}?${query}`, {
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (!response.ok) {
        const message = await response.text();
        throw new Error(message || `Request failed (${response.status})`);
    }

    return response.json();
};

const renderList = (container, items, emptyText) => {
    if (!container) {
        return;
    }

    container.innerHTML = '';

    if (!items.length) {
        const empty = document.createElement('div');
        empty.className = 'gv-utilities-bubble__empty';
        empty.textContent = emptyText;
        container.appendChild(empty);
        return;
    }

    items.forEach((item) => {
        const row = document.createElement('button');
        row.type = 'button';
        row.className = 'gv-utilities-bubble__list-row';
        row.dataset.route = item.conversation_route || '';
        row.innerHTML = `
            <div>
                <p class="gv-utilities-bubble__list-title">${item.participant || item.from_user || '—'}</p>
                ${
                    item.last_message_at
                        ? `<p class="gv-utilities-bubble__list-meta">${item.last_message_at}</p>`
                        : ''
                }
            </div>
            <svg viewBox="0 0 20 20" aria-hidden="true">
                <path d="m7.5 4.5 5 5-5 5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        `;

        row.addEventListener('click', () => {
            const target = row.dataset.route || container.dataset.inbox;
            if (target) {
                window.location.href = target;
            }
        });

        container.appendChild(row);
    });
};

const renderQuickTools = (container, items, emptyText) => {
    if (!container) {
        return;
    }

    container.innerHTML = '';

    if (!items.length) {
        const empty = document.createElement('div');
        empty.className = 'gv-utilities-bubble__empty';
        empty.textContent = emptyText;
        container.appendChild(empty);
        return;
    }

    items.forEach((item) => {
        const row = document.createElement('button');
        row.type = 'button';
        row.className = 'gv-utilities-bubble__list-row';
        row.dataset.route = item.href || '';
        row.innerHTML = `
            <div>
                <p class="gv-utilities-bubble__list-title">${item.label || '—'}</p>
                ${
                    item.description
                        ? `<p class="gv-utilities-bubble__list-meta">${item.description}</p>`
                        : ''
                }
            </div>
            <svg viewBox="0 0 20 20" aria-hidden="true">
                <path d="m7.5 4.5 5 5-5 5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        `;

        row.addEventListener('click', () => {
            if (row.dataset.route) {
                window.location.href = row.dataset.route;
            }
        });

        container.appendChild(row);
    });
};

const updateBadge = (badge, conversations, requests) => {
    if (!badge) {
        return;
    }

    const total = (requests?.length ?? 0) + (conversations?.length ?? 0);
    if (total > 0) {
        badge.hidden = false;
        badge.textContent = total > 9 ? '9+' : total.toString();
    } else {
        badge.hidden = true;
    }
};

const initBubble = (root) => {
    const endpoints = JSON.parse(root.dataset.endpoints || '{}');
    const toggle = root.querySelector('[data-role="toggle"]');
    const panel = root.querySelector('[data-role="bubble-panel"]');
    const closeBtn = root.querySelector('[data-action="close"]');
    const inboxButtons = root.querySelectorAll('[data-action="open-inbox"]');
    const convList = root.querySelector('[data-role="conversations"]');
    const reqList = root.querySelector('[data-role="requests"]');
    const badge = root.querySelector('[data-role="bubble-count"]');
    const inboxUrl = root.dataset.inboxUrl;
    const errorBox = root.querySelector('[data-role="bubble-error"]');
    const quickToolsSection = root.querySelector('[data-role="quick-tools-section"]');
    const quickToolsList = root.querySelector('[data-role="quick-tools"]');
    const quickToolsEndpoint = root.dataset.quickToolsUrl;
    const quickToolsContext = root.dataset.quickToolsContext || 'global';

    let isOpen = false;
    let hasLoaded = false;

    const setOpen = (value) => {
        isOpen = value;
        root.classList.toggle('is-open', value);
        panel.hidden = !value;
        toggle.setAttribute('aria-expanded', value ? 'true' : 'false');

        if (value && !hasLoaded) {
            hydrate();
        }
    };

    const hydrate = async () => {
        root.classList.add('is-loading');
        errorBox.textContent = '';

        try {
            const quickToolsPromise = quickToolsEndpoint
                ? fetchQuickTools(quickToolsEndpoint, quickToolsContext)
                : Promise.resolve(null);

            const [conversations, requests] = await Promise.all([
                fetchCollection(endpoints.conversations ?? ''),
                fetchCollection(endpoints.requests ?? ''),
            ]);
            const quickToolsPayload = await quickToolsPromise;

            renderList(convList, conversations, root.dataset.emptyConversations || 'No recent chats');
            renderList(reqList, requests, root.dataset.emptyRequests || 'No pending requests');
            updateBadge(badge, conversations, requests);

            if (quickToolsSection) {
                const quickToolActions = quickToolsPayload?.actions ?? [];
                if (quickToolActions.length) {
                    quickToolsSection.hidden = false;
                    renderQuickTools(
                        quickToolsList,
                        quickToolActions,
                        root.dataset.emptyQuickTools || 'No quick tools available.',
                    );
                } else {
                    quickToolsSection.hidden = true;
                }
            }

            hasLoaded = true;
        } catch (error) {
            if (errorBox) {
                errorBox.textContent =
                    error instanceof Error ? error.message : 'Unable to load utilities data right now.';
            }
        } finally {
            root.classList.remove('is-loading');
        }
    };

    toggle?.addEventListener('click', () => setOpen(!isOpen));
    closeBtn?.addEventListener('click', () => setOpen(false));

    inboxButtons.forEach((button) =>
        button.addEventListener('click', () => {
            if (inboxUrl) {
                window.location.href = inboxUrl;
            }
        }),
    );

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen) {
            setOpen(false);
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll(bubbleSelector).forEach((bubble) => initBubble(bubble));
});


