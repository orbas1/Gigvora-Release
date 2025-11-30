const defaultManifest = () => ({
    filter: 'none',
    overlays: [],
    music: null,
    crop: { aspect: '9:16' },
    stickers: [],
    gifs: [],
});

const MediaStudio = () => {
    const studioEl = document.getElementById('gvComposerStudio');
    if (!studioEl) {
        return;
    }

    const modeInput = document.getElementById('composer_mode');
    const resolutionInput = document.getElementById('resolution_preset');
    const manifestInput = document.getElementById('studio_manifest');
    const stickers = JSON.parse(studioEl.dataset.studioStickers || '[]');
    const gifs = JSON.parse(studioEl.dataset.studioGifs || '[]');

    const manifests = {
        story: defaultManifest(),
        reel: defaultManifest(),
        longform: defaultManifest(),
    };

    let activeMode = 'standard';

    const setActiveMode = (mode) => {
        activeMode = mode;
        studioEl.dataset.activeMode = mode;
        modeInput.value = mode;
        updateManifestInput();
    };

    const updateManifestInput = () => {
        if (manifests[activeMode]) {
            manifestInput.value = JSON.stringify(manifests[activeMode]);
        } else {
            manifestInput.value = '';
        }
    };

    const overlayList = (mode) => studioEl.querySelector(`[data-overlay-list="${mode}"]`);

    const renderOverlayList = (mode) => {
        const listEl = overlayList(mode);
        if (!listEl) {
            return;
        }

        listEl.innerHTML = '';
        const overlays = manifests[mode]?.overlays ?? [];
        overlays.forEach((overlay) => {
            const pill = document.createElement('div');
            pill.className = 'gv-overlay-pill';
            pill.dataset.id = overlay.id;
            pill.innerHTML = `
                <span>${overlay.type === 'text' ? overlay.value : overlay.type}</span>
                <button type="button" aria-label="Remove" data-remove-overlay="${overlay.id}">&times;</button>
            `;
            listEl.appendChild(pill);
        });
    };

    const addOverlay = (mode, overlay) => {
        if (!manifests[mode]) {
            manifests[mode] = defaultManifest();
        }
        overlay.id = `${mode}-${Date.now()}-${Math.random()}`;
        manifests[mode].overlays.push(overlay);
        renderOverlayList(mode);
        updateManifestInput();
    };

    const removeOverlay = (mode, overlayId) => {
        if (!manifests[mode]) {
            return;
        }
        manifests[mode].overlays = manifests[mode].overlays.filter((overlay) => overlay.id !== overlayId);
        renderOverlayList(mode);
        updateManifestInput();
    };

    studioEl.addEventListener('click', (event) => {
        const tabButton = event.target.closest('.gv-composer-studio__tab');
        if (tabButton) {
            studioEl.querySelectorAll('.gv-composer-studio__tab').forEach((btn) => btn.classList.remove('is-active'));
            tabButton.classList.add('is-active');
            const mode = tabButton.dataset.mode;
            setActiveMode(mode);
            return;
        }

        const resolutionButton = event.target.closest('[data-resolution-selector] button');
        if (resolutionButton) {
            const container = resolutionButton.closest('[data-resolution-selector]');
            container.querySelectorAll('button').forEach((btn) => btn.classList.remove('is-active'));
            resolutionButton.classList.add('is-active');
            resolutionInput.value = resolutionButton.dataset.value;
            return;
        }

        const aspectButton = event.target.closest('[data-studio-aspect] button');
        if (aspectButton) {
            const container = aspectButton.closest('[data-studio-aspect]');
            container.querySelectorAll('button').forEach((btn) => btn.classList.remove('is-active'));
            aspectButton.classList.add('is-active');
            const mode = container.dataset.studioAspect;
            if (!manifests[mode]) {
                manifests[mode] = defaultManifest();
            }
            manifests[mode].crop.aspect = aspectButton.dataset.value;
            updateManifestInput();
            return;
        }

        const overlayAction = event.target.closest('[data-action]');
        if (overlayAction) {
            const mode = overlayAction.closest('[data-overlay-target]').dataset.overlayTarget;
            handleOverlayAction(mode, overlayAction.dataset.action);
            return;
        }

        const removeOverlayButton = event.target.closest('[data-remove-overlay]');
        if (removeOverlayButton) {
            const mode = removeOverlayButton.closest('[data-overlay-list]').dataset.overlayList;
            removeOverlay(mode, removeOverlayButton.dataset.removeOverlay);
            return;
        }

        if (event.target.matches('[data-add-cta]')) {
            const list = studioEl.querySelector('[data-live-cta-list]');
            if (list) {
                const index = list.querySelectorAll('.gv-composer-cta-row').length;
                const row = document.createElement('div');
                row.className = 'gv-composer-cta-row';
                row.innerHTML = `
                    <input type="text" name="live_cta_links[${index}][label]" class="gv-input" placeholder="${event.target.dataset.label ?? 'Label'}">
                    <input type="url" name="live_cta_links[${index}][url]" class="gv-input" placeholder="https://">
                `;
                list.appendChild(row);
            }
        }
    });

    studioEl.querySelectorAll('[data-studio-filter]').forEach((select) => {
        select.addEventListener('change', (event) => {
            const mode = event.target.dataset.studioFilter;
            if (!manifests[mode]) {
                manifests[mode] = defaultManifest();
            }
            manifests[mode].filter = event.target.value;
            updateManifestInput();
        });
    });

    studioEl.querySelectorAll('[data-studio-music]').forEach((select) => {
        select.addEventListener('change', (event) => {
            const mode = event.target.dataset.studioMusic;
            if (!manifests[mode]) {
                manifests[mode] = defaultManifest();
            }
            manifests[mode].music = event.target.value || null;
            updateManifestInput();
        });
    });

    const handleOverlayAction = (mode, action) => {
        if (!manifests[mode]) {
            manifests[mode] = defaultManifest();
        }

        if (action === 'text') {
            const value = prompt('Enter overlay text');
            if (!value) return;
            const color = prompt('Text color (hex)', '#ffffff') || '#ffffff';
            addOverlay(mode, { type: 'text', value, color, size: 18, x: 0.5, y: 0.5 });
            return;
        }

        if (action === 'emoji') {
            const emoji = prompt('Enter emoji (e.g. 🎉)');
            if (!emoji) return;
            addOverlay(mode, { type: 'emoji', value: emoji, size: 24, x: 0.5, y: 0.5 });
            return;
        }

        if (action === 'sticker') {
            const selection = prompt(`Available stickers: ${stickers.join(', ')}`);
            if (!selection || !stickers.includes(selection)) {
                return;
            }
            manifests[mode].stickers.push(selection);
            updateManifestInput();
            renderOverlayList(mode);
            return;
        }

        if (action === 'gif') {
            const selection = prompt(`Available GIFs: ${gifs.join(', ')}`);
            if (!selection || !gifs.includes(selection)) {
                return;
            }
            manifests[mode].gifs.push(selection);
            updateManifestInput();
            renderOverlayList(mode);
        }
    };

    setActiveMode('standard');
};

document.addEventListener('DOMContentLoaded', MediaStudio);

