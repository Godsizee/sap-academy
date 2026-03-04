export function initThemeManager() {
    // Greife auf das bereits vom Inline-Skript erstellte Link-Tag zu
    const themeLink = document.getElementById('dynamic-theme');

    const loadThemeFile = (themeFile) => {
        if (themeLink) themeLink.href = `/css/themes/${themeFile}.css`;
    };

    const applyThemeMode = (mode) => {
        document.body.classList.remove('light-mode', 'focus-mode');
        if (mode === 'light') {
            document.body.classList.add('light-mode');
        } else if (mode === 'focus') {
            document.body.classList.add('focus-mode');
        }
    };

    const initializeTheme = () => {
        const savedTheme = localStorage.getItem('selectedTheme') || 'default';
        const savedMode = localStorage.getItem('themeMode') || 'default';

        document.body.className = `theme-${savedTheme}`; 
        loadThemeFile(savedTheme);
        applyThemeMode(savedMode);
    };

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