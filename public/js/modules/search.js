/**
 * Search Modul
 * Kümmert sich um die Text-Hervorhebung und das Durchsuchen der Seite.
 */

export function initSearch() {
    const siteSearchInput = document.getElementById('siteSearchInput');
    const searchControls = document.querySelector('.search-controls');
    const searchCounter = document.getElementById('searchCounter');
    const searchNext = document.getElementById('searchNext');
    const searchPrev = document.getElementById('searchPrev');

    if (!siteSearchInput || !searchControls) return;

    const searchableBlocks = document.querySelectorAll('.searchable-block');
    const originalContent = new Map();
    
    // Originalinhalte sichern, um Markierungen später entfernen zu können
    searchableBlocks.forEach((block, i) => originalContent.set(i, block.innerHTML));
    
    let matches = [];
    let currentIndex = -1;

    const clearSearch = () => {
        searchableBlocks.forEach((block, i) => {
            if (originalContent.has(i)) { block.innerHTML = originalContent.get(i); }
        });
        searchControls.classList.remove('visible');
        matches = [];
        currentIndex = -1;
    };

    const jumpToMatch = () => {
        if (currentIndex === -1 || matches.length === 0) return;
        matches.forEach(mark => mark.classList.remove('mark-active'));
        
        const activeMatch = matches[currentIndex];
        activeMatch.classList.add('mark-active');
        activeMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
        searchCounter.textContent = `${currentIndex + 1} / ${matches.length}`;
    };

    siteSearchInput.addEventListener('input', () => {
        const query = siteSearchInput.value.trim();
        
        // Immer zuerst den Originalzustand wiederherstellen
        searchableBlocks.forEach((block, i) => {
            if (originalContent.has(i)) { block.innerHTML = originalContent.get(i); }
        });

        if (query.length < 3) { 
            clearSearch(); 
            return; 
        }

        const regex = new RegExp(query, 'gi');
        searchableBlocks.forEach((block, i) => {
            const originalHTML = originalContent.get(i);
            // Einfache Markierung - ignoriert HTML-Tags (für produktiven Einsatz ggf. robusteren Parser nutzen)
            if (originalHTML.toLowerCase().includes(query.toLowerCase())) {
                const newHTML = originalHTML.replace(regex, match => `<mark>${match}</mark>`);
                block.innerHTML = newHTML;
            }
        });

        matches = Array.from(document.querySelectorAll('.searchable-block mark'));
        
        if (matches.length > 0) {
            searchControls.classList.add('visible');
            currentIndex = 0;
            jumpToMatch();
        } else {
            searchControls.classList.remove('visible');
            searchCounter.textContent = '0 / 0';
        }
    });

    searchNext.addEventListener('click', () => {
        if (matches.length > 0) {
            currentIndex = (currentIndex + 1) % matches.length;
            jumpToMatch();
        }
    });

    searchPrev.addEventListener('click', () => {
        if (matches.length > 0) {
            currentIndex = (currentIndex - 1 + matches.length) % matches.length;
            jumpToMatch();
        }
    });
}