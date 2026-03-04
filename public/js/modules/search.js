/**
 * Search Modul
 * Nutzt einen DOM TreeWalker für kugelsichere Text-Hervorhebung ohne HTML-Tags zu beschädigen.
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
    
    // Originalinhalte sichern
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

    // Escaped Sonderzeichen für reguläre Ausdrücke
    const escapeRegExp = (string) => string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    siteSearchInput.addEventListener('input', () => {
        const query = siteSearchInput.value.trim();
        
        clearSearch();

        if (query.length < 3) return;

        const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');

        searchableBlocks.forEach(block => {
            // TreeWalker durchsucht ausschließlich Text-Nodes, ignoriert DOM-Struktur
            const walker = document.createTreeWalker(block, NodeFilter.SHOW_TEXT, null, false);
            const nodesToReplace = [];

            let node;
            while (node = walker.nextNode()) {
                // Skripte und bereits markierte Elemente überspringen
                if (node.parentNode.nodeName === 'MARK' || node.parentNode.nodeName === 'SCRIPT') continue;
                if (regex.test(node.nodeValue)) {
                    nodesToReplace.push(node);
                }
            }

            nodesToReplace.forEach(textNode => {
                const fragment = document.createDocumentFragment();
                const parts = textNode.nodeValue.split(regex);
                
                parts.forEach(part => {
                    if (part.toLowerCase() === query.toLowerCase()) {
                        const mark = document.createElement('mark');
                        mark.textContent = part;
                        fragment.appendChild(mark);
                    } else if (part) {
                        fragment.appendChild(document.createTextNode(part));
                    }
                });
                textNode.parentNode.replaceChild(fragment, textNode);
            });
        });

        matches = Array.from(document.querySelectorAll('.searchable-block mark'));
        
        if (matches.length > 0) {
            searchControls.classList.add('visible');
            currentIndex = 0;
            jumpToMatch();
        } else {
            searchControls.classList.add('visible');
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