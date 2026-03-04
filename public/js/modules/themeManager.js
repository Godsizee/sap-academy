/**
 * Theme Manager
 * Kümmert sich ausschließlich um das Laden und Umschalten von Themes und Modi.
 * DSGVO-Hinweis: Speichert ausschließlich anonyme Design-Präferenzen im LocalStorage.
 */

export function initThemeManager() {
    const head = document.head;
    const themeLink = document.createElement('link');
    themeLink.rel = 'stylesheet';
    themeLink.id = 'dynamic-theme';
    head.appendChild(themeLink);

    // Lädt eine CSS-Datei für ein bestimmtes Theme
    const loadThemeFile = (themeFile) => {
        themeLink.href = `/css/themes/${themeFile}.css`;
    };

    // Wendet den aktuellen Modus (default, light, focus) an
    const applyThemeMode = (mode) => {
        document.body.classList.remove('light-mode', 'focus-mode');
        if (mode === 'light') {
            document.body.classList.add('light-mode');
        } else if (mode === 'focus') {
            document.body.classList.add('focus-mode');
        }
    };

    // Initialisiert das Theme und den Modus beim Laden der Seite
    const initializeTheme = () => {
        const savedTheme = localStorage.getItem('selectedTheme') || 'default';
        const savedMode = localStorage.getItem('themeMode') || 'default';

        document.body.className = `theme-${savedTheme}`; 
        loadThemeFile(savedTheme);
        applyThemeMode(savedMode);
    };

    // Initiale Ausführung
    initializeTheme();

    // --- Event Listener: Header-Theme-Umschalter ---
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentMode = localStorage.getItem('themeMode') || 'default';
            let newMode = currentMode === 'default' ? 'light' : (currentMode === 'light' ? 'focus' : 'default');

            localStorage.setItem('themeMode', newMode);
            applyThemeMode(newMode);
        });
    }

    // --- Event Listener: Theme-Auswahlfenster (Modal) ---
    const themeSelectionOverlay = document.getElementById('themeSelectionOverlay');
    const themeSelectionForm = document.getElementById('themeSelectionForm');
    const changeThemeBtn = document.getElementById('changeThemeBtn');

    if (changeThemeBtn && themeSelectionOverlay) {
        changeThemeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            themeSelectionOverlay.classList.add('visible');
        });
    }

    if (themeSelectionOverlay && themeSelectionForm) {
        themeSelectionForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const selectedThemeInput = themeSelectionForm.querySelector('input[name="theme"]:checked');
            if (selectedThemeInput) {
                const themeName = selectedThemeInput.value;
                localStorage.setItem('selectedTheme', themeName);
                localStorage.setItem('themeMode', 'default');
                initializeTheme(); 
                themeSelectionOverlay.classList.remove('visible');
            }
        });
    }
}