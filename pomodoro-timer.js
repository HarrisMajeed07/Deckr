const STORAGE_KEY = 'deckr_pomodoro';

function getState() {
  const raw = localStorage.getItem(STORAGE_KEY);
  if (!raw) return null;
  return JSON.parse(raw);
}

function saveState(state) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
}

function formatTime(seconds) {
  const m = Math.floor(seconds / 60).toString().padStart(2, '0');
  const s = (seconds % 60).toString().padStart(2, '0');
  return `${m}:${s}`;
}

function injectNavTimer() {
  const isPomodoro = window.location.pathname.includes('pomodoro.html');
  if (isPomodoro) return;

  const state = getState();
  if (!state) return;

  const nav = document.querySelector('.navbar');
  if (!nav || document.getElementById('nav-pomodoro')) return;

  const mini = document.createElement('a');
  mini.id = 'nav-pomodoro';
  mini.href = 'pomodoro.html';
  mini.title = 'Go to Pomodoro';
  mini.style.cssText = `
    display: flex;
    align-items: center;
    gap: 6px;
    background: #e74c3c;
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.95rem;
    text-decoration: none;
    margin-left: 16px;
    transition: background 0.2s;
  `;
  mini.onmouseenter = () => mini.style.background = '#c0392b';
  mini.onmouseleave = () => mini.style.background = '#e74c3c';
  const navActions = nav.querySelector('.nav-actions');
  nav.insertBefore(mini, navActions);
}

function startNavTick() {
  const isPomodoro = window.location.pathname.includes('pomodoro.html');
  if (isPomodoro) return;

  setInterval(() => {
    const state = getState();
    if (!state || !state.running) return;

    const now = Date.now();
    const elapsed = Math.floor((now - state.lastTick) / 1000);
    if (elapsed < 1) return;

    state.remaining = Math.max(0, state.remaining - elapsed);
    state.lastTick = now;

    if (state.remaining === 0) {
      state.running = false;
    }

    saveState(state);

    const display = document.getElementById('nav-timer-display');
    if (display) {
      display.textContent = formatTime(state.remaining);
    } else {
      injectNavTimer();
    }
  }, 1000);
}

document.addEventListener('DOMContentLoaded', () => {
  injectNavTimer();
  startNavTick();
});