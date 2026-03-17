export function initThemeManager() {
    const applyThemeMode = (mode) => {
        document.body.classList.remove('light-mode', 'dark-mode', 'focus-mode');
        // 'default' ist bei uns der Light-Mode, wir setzen aber explizit Klassen
        if (mode === 'dark') {
            document.body.classList.add('dark-mode');
        } else if (mode === 'focus') {
            document.body.classList.add('focus-mode');
        }
        localStorage.setItem('themeMode', mode);
    };

    // Initialisierung beim Laden
    const savedMode = localStorage.getItem('themeMode') || 'default';
    applyThemeMode(savedMode);

    // Toggle-Logik (Header Schalter)
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const currentMode = localStorage.getItem('themeMode') || 'default';
            let nextMode;
            if (currentMode === 'default') nextMode = 'dark';
            else if (currentMode === 'dark') nextMode = 'focus';
            else nextMode = 'default';
            
            applyThemeMode(nextMode);
        });
    }

    // Theme-Auswahl Modal (Wir entfernen den Theme-Wechsel und lassen nur noch den Modus-Wechsel)
    const changeThemeBtn = document.getElementById('changeThemeBtn');
    if (changeThemeBtn) {
        // Da wir nur noch ein Theme haben, können wir diesen Button 
        // entweder entfernen oder für die Modus-Wahl (Hell/Dunkel/Fokus) nutzen.
        changeThemeBtn.style.display = 'none'; 
    }
}