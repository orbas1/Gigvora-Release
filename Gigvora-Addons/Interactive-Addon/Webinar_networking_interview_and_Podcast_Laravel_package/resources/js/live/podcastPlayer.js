const playerRoot = document.getElementById('podcast-episode');
const seriesRoot = document.getElementById('podcast-series');
const recordingPlayer = document.getElementById('recording-player');

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

const formatTime = (totalSeconds) => {
    const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
    const seconds = String(Math.floor(totalSeconds % 60)).padStart(2, '0');
    return `${minutes}:${seconds}`;
};

if (playerRoot) {
    const toggleBtn = document.getElementById('audio-toggle');
    const progress = document.getElementById('audio-progress');
    const timeLabel = document.getElementById('audio-time');
    const speedSelect = document.getElementById('audio-speed');
    const audio = document.getElementById('podcast-audio');
    const analyticsEndpoint = playerRoot.dataset.analyticsEndpoint;
    const storageKey = `podcast-${playerRoot.dataset.episodeId}-progress`;

    const reportPlayback = async (completed = false) => {
        if (!analyticsEndpoint) return;

        try {
            await fetch(analyticsEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    progress_seconds: Math.floor(audio?.currentTime || 0),
                    completed,
                }),
            });
        } catch (error) {
            console.debug('Analytics skip', error);
        }
    };

    const syncButton = () => {
        if (!toggleBtn || !audio) return;
        toggleBtn.textContent = audio.paused ? 'Play' : 'Pause';
    };

    const renderTime = () => {
        if (!timeLabel || !progress || !audio || Number.isNaN(audio.duration)) return;
        const duration = Math.max(audio.duration || 0, 1);
        const elapsed = Math.min(duration, audio.currentTime || 0);
        timeLabel.textContent = `${formatTime(elapsed)} / ${formatTime(duration)}`;
        progress.value = (elapsed / duration) * 100;
    };

    if (audio) {
        const savedPosition = Number(localStorage.getItem(storageKey) || 0);
        if (savedPosition && !Number.isNaN(savedPosition)) {
            audio.currentTime = savedPosition;
        }

        audio.addEventListener('loadedmetadata', renderTime);
        audio.addEventListener('timeupdate', () => {
            localStorage.setItem(storageKey, String(audio.currentTime));
            renderTime();
        });
        audio.addEventListener('ended', () => {
            localStorage.removeItem(storageKey);
            reportPlayback(true);
            syncButton();
        });
    }

    toggleBtn?.addEventListener('click', () => {
        if (!audio) return;
        if (audio.paused) {
            audio.play().then(reportPlayback).catch(() => undefined);
        } else {
            audio.pause();
            reportPlayback();
        }
        syncButton();
    });

    progress?.addEventListener('input', () => {
        if (!audio) return;
        const duration = audio.duration || 0;
        audio.currentTime = (Number(progress.value) / 100) * duration;
        renderTime();
    });

    speedSelect?.addEventListener('change', () => {
        if (!audio) return;
        audio.playbackRate = Number(speedSelect.value);
    });

    renderTime();
    syncButton();
}

if (seriesRoot) {
    const followBtn = document.getElementById('follow-series');
    const followUrl = seriesRoot.dataset.followUrl;
    const followersLabel = document.getElementById('series-followers-count');
    let isFollowed = seriesRoot.dataset.followed === 'true';

    const renderFollow = (countText) => {
        if (followBtn) {
            followBtn.textContent = isFollowed ? 'Following' : 'Follow';
            followBtn.setAttribute('aria-pressed', String(isFollowed));
        }

        if (countText && followersLabel) {
            followersLabel.textContent = countText;
        }
    };

    followBtn?.addEventListener('click', async () => {
        if (!followUrl || !followBtn) return;
        followBtn.disabled = true;

        try {
            const response = await fetch(followUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
            });

            if (response.ok) {
                const data = await response.json();
                isFollowed = Boolean(data.followed);
                const count = data.followers_count ?? followersLabel?.dataset.followersCount;
                if (followersLabel && typeof count !== 'undefined') {
                    followersLabel.dataset.followersCount = String(count);
                    followersLabel.textContent = `${count} ${Number(count) === 1 ? 'follower' : 'followers'}`;
                }
            }
        } catch (error) {
            console.warn('Follow toggle failed', error);
        } finally {
            followBtn.disabled = false;
            renderFollow();
        }
    });

    renderFollow();
}

if (recordingPlayer) {
    const speedButtons = recordingPlayer.querySelectorAll('[data-speed]');
    speedButtons.forEach((btn) =>
        btn.addEventListener('click', () => speedButtons.forEach((b) => b.classList.toggle('active', b === btn))),
    );
    document.getElementById('recording-chapters')?.addEventListener('click', (event) => {
        if (event.target.matches('[data-seek]')) {
            event.preventDefault();
            console.log('Seek to', event.target.dataset.seek);
        }
    });
}
