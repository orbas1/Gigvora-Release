const liveRoot = document.getElementById('podcast-live');

if (liveRoot) {
    const recordBtn = document.getElementById('toggle-record');
    const muteBtn = document.getElementById('mute-guests');
    const timerLabel = document.getElementById('recording-timer');
    const statusPill = document.getElementById('recording-status');

    let recording = false;
    let seconds = 0;

    const renderTimer = () => {
        const minutes = String(Math.floor(seconds / 60)).padStart(2, '0');
        const remainder = String(Math.floor(seconds % 60)).padStart(2, '0');
        if (timerLabel) {
            timerLabel.textContent = `${minutes}:${remainder}`;
        }
    };

    const toggleRecording = () => {
        recording = !recording;
        if (recordBtn) {
            recordBtn.textContent = recording ? 'Stop Recording' : 'Record / Stop';
        }
        if (statusPill) {
            statusPill.textContent = recording ? 'Recording' : 'Live';
            statusPill.classList.toggle('gv-pill--danger', recording);
            statusPill.classList.toggle('gv-pill--success', !recording);
        }
    };

    recordBtn?.addEventListener('click', () => {
        toggleRecording();
    });

    muteBtn?.addEventListener('click', () => {
        muteBtn.classList.toggle('gv-btn-primary');
        muteBtn.classList.toggle('gv-btn-ghost');
        muteBtn.textContent = muteBtn.classList.contains('gv-btn-primary') ? 'Guests unmuted' : 'Mute guests';
    });

    setInterval(() => {
        if (!recording) return;
        seconds += 1;
        renderTimer();
    }, 1000);

    renderTimer();
}
