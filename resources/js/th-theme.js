const STORAGE_KEY = 'th-theme';

function getSystemTheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function getStoredTheme() {
    try {
        return localStorage.getItem(STORAGE_KEY);
    } catch {
        return null;
    }
}

function applyTheme(theme) {
    const resolved = theme === 'dark' ? 'dark' : 'light';
    document.documentElement.classList.toggle('dark', resolved === 'dark');
    document.documentElement.setAttribute('data-theme', resolved);

    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        const icon = btn.querySelector('[data-theme-icon]');
        if (icon) {
            icon.className = resolved === 'dark'
                ? 'ph ph-sun text-lg'
                : 'ph ph-moon text-lg';
        }
        btn.setAttribute('aria-label', resolved === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
    });
}

function initTheme() {
    const stored = getStoredTheme();
    applyTheme(stored || getSystemTheme());
}

function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    const next = isDark ? 'light' : 'dark';
    try {
        localStorage.setItem(STORAGE_KEY, next);
    } catch {
        /* ignore */
    }
    applyTheme(next);
}

initTheme();

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!getStoredTheme()) {
        applyTheme(e.matches ? 'dark' : 'light');
    }
});

window.toggleTheme = toggleTheme;
window.applyTheme = applyTheme;

export { initTheme, toggleTheme, applyTheme };
