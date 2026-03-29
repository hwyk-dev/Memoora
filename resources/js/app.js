import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

window.Alpine = Alpine;
Alpine.plugin(focus);

// Global confirm-modal store — replaces window.confirm() everywhere.
Alpine.store('confirm', {
    open: false,
    title: '',
    message: '',
    confirmLabel: 'Confirm',
    danger: true,
    _cb: null,

    ask({ title, message, confirmLabel = 'Confirm', danger = true, onConfirm }) {
        this.title        = title;
        this.message      = message;
        this.confirmLabel = confirmLabel;
        this.danger       = danger;
        this._cb          = onConfirm || null;
        this.open         = true;
    },

    resolve() {
        this.open = false;
        if (this._cb) this._cb();
        this._cb = null;
    },

    dismiss() {
        this.open = false;
        this._cb  = null;
    },
});

Alpine.start();

// ---------------------------------------------------------------------------
// Dark mode — apply saved preference before first paint (also in <head>),
// and wire up the toggle button once the DOM is ready.
// ---------------------------------------------------------------------------
(function () {
    function applyTheme(dark) {
        document.documentElement.classList.toggle('dark', dark);
    }

    // Sync from localStorage on every navigation (SPA-style back/forward safe)
    const saved = localStorage.getItem('theme');
    if (saved) {
        applyTheme(saved === 'dark');
    } else {
        applyTheme(window.matchMedia('(prefers-color-scheme: dark)').matches);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('darkModeToggle');
        if (!btn) return;

        btn.addEventListener('click', function () {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    });
})();
