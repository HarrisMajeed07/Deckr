<?php require_once "header.html"; ?>

    <div class="pomodoro-page">
        <div class="timer-container">
            <h2 class="timer-title">Pomodoro Timer</h2>
            <div id="timer-display">25:00</div>
            <div class="timer-buttons">
                <button id="start-btn">Start</button>
                <button id="pause-btn" disabled>Pause</button>
                <button id="reset-btn">Reset</button>
            </div>
        </div>
    </div>

    <script>
        const STORAGE_KEY = 'deckr_pomodoro';
        let interval = null;

        const display   = document.getElementById('timer-display');
        const startBtn  = document.getElementById('start-btn');
        const pauseBtn  = document.getElementById('pause-btn');
        const resetBtn  = document.getElementById('reset-btn');

        function getState() {
            const raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : { remaining: 25 * 60, running: false, paused: false, lastTick: null };
        }

        function saveState(state) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        }

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function updateButtons(state) {
            if (!state.running && !state.paused) {
                // Idle
                startBtn.disabled = false;
                startBtn.textContent = 'Start';
                pauseBtn.disabled = true;
                pauseBtn.textContent = 'Pause';
            } else if (state.running && !state.paused) {
                // Running
                startBtn.disabled = true;
                pauseBtn.disabled = false;
                pauseBtn.textContent = 'Pause';
            } else if (state.paused) {
                // Paused
                startBtn.disabled = true;
                pauseBtn.disabled = false;
                pauseBtn.textContent = 'Continue';
            }
        }

        function render() {
            const state = getState();
            if (display) display.textContent = formatTime(state.remaining);
            updateButtons(state);
        }

        function startTimer() {
            let state = getState();
            state.running  = true;
            state.paused   = false;
            state.lastTick = Date.now();
            saveState(state);
            updateButtons(state);

            clearInterval(interval);
            interval = setInterval(() => {
                state = getState();
                if (!state.running || state.paused) { clearInterval(interval); return; }

                const now     = Date.now();
                const elapsed = Math.floor((now - state.lastTick) / 1000);
                if (elapsed < 1) return;

                state.remaining = Math.max(0, state.remaining - elapsed);
                state.lastTick  = now;

                if (state.remaining === 0) {
                    state.running = false;
                    state.paused  = false;
                    clearInterval(interval);
                    saveState(state);
                    render();
                    alert('Pomodoro complete! Take a break 🎉');
                    return;
                }

                saveState(state);
                render();
            }, 500);
        }

        function pauseTimer() {
            let state = getState();
            if (state.paused) {
                // Resume
                state.paused   = false;
                state.running  = true;
                state.lastTick = Date.now();
                saveState(state);
                startTimer();
            } else {
                // Pause
                state.paused  = true;
                state.running = false;
                clearInterval(interval);
                saveState(state);
                updateButtons(state);
            }
        }

        function resetTimer() {
            clearInterval(interval);
            saveState({ remaining: 25 * 60, running: false, paused: false, lastTick: null });
            render();
        }

        startBtn.addEventListener('click', () => {
            const state = getState();
            if (!state.running && !state.paused) startTimer();
        });

        pauseBtn.addEventListener('click', pauseTimer);
        resetBtn.addEventListener('click', resetTimer);

        window.addEventListener('DOMContentLoaded', () => {
            const state = getState();
            if (state.running) startTimer();
            render();
        });
    </script>

</body>
</html>