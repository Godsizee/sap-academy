/**
 * Haupt-Einstiegspunkt der SAP Academy
 * Prinzip: SRP (Dieses Modul initialisiert nur, es enthält keine eigene Logik)
 */

import { initThemeManager } from './modules/themeManager.js';
import { initNavigation } from './modules/navigation.js';
import { initSearch } from './modules/search.js';
import { initUIComponents } from './modules/uiComponents.js';
import { initQuizEngine } from './modules/quizEngine.js';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Theme & Design initialisieren
    initThemeManager();
    
    // 2. Layout & Navigation (Burger-Menü, Scroll-Spy, Back-to-Top)
    initNavigation();
    
    // 3. Globale Suchfunktion
    initSearch();
    
    // 4. UI-Komponenten (Akkordeons, Popovers, Animationen)
    initUIComponents();
    
    // 5. Quiz-Engine (wird nur aktiv, wenn ein Quiz auf der Seite existiert)
    initQuizEngine();
});