const playerRoot = document.getElementById('podcast-episode');
const seriesRoot = document.getElementById('podcast-series');
const recordingPlayer = document.getElementById('recording-player');

if (playerRoot) {
    const toggleBtn = document.getElementById('audio-toggle');
    const progress = document.getElementById('audio-progress');
    const timeLabel = document.getElementById('audio-time');
    const speedSelect = document.getElementById('audio-speed');
    let playing = false;
    let position = Number(localStorage.getItem('episode-progress') || 0);
    const duration = 1800;

    const renderTime = () => {
        const elapsed = Math.min(duration, position);
        const minutes = (secs) => String(Math.floor(secs / 60)).padStart(2, '0');
        const seconds = (secs) => String(Math.floor(secs % 60)).padStart(2, '0');
        timeLabel.textContent = `${minutes(elapsed)}:${seconds(elapsed)} / ${minutes(duration)}:${seconds(duration)}`;
        progress.value = (elapsed / duration) * 100;
    };

    toggleBtn?.addEventListener('click', () => {
        playing = !playing;
        toggleBtn.textContent = playing ? 'Pause' : 'Play';
    });

    progress?.addEventListener('input', () => {
        position = (progress.value / 100) * duration;
        renderTime();
    });

    speedSelect?.addEventListener('change', () => {
        const speed = Number(speedSelect.value);
        console.log('Playback speed', speed);
    });

    setInterval(() => {
        if (!playing) return;
        position += 1;
        localStorage.setItem('episode-progress', position);
        renderTime();
    }, 1000);

    renderTime();
}

if (seriesRoot) {
    const followBtn = document.getElementById('follow-series');
    followBtn?.addEventListener('click', () => {
        followBtn.textContent = 'Following';
    });
}

if (recordingPlayer) {
    const speedButtons = recordingPlayer.querySelectorAll('[data-speed]');
    speedButtons.forEach((btn) => btn.addEventListener('click', () => speedButtons.forEach((b) => b.classList.toggle('active', b === btn))));
    document.getElementById('recording-chapters')?.addEventListener('click', (event) => {
        if (event.target.matches('[data-seek]')) {
            event.preventDefault();
            console.log('Seek to', event.target.dataset.seek);
        }
    });
}
