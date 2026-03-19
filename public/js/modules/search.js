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
    
    let matches = [];
    let currentIndex = -1;

    // SICHERE METHODE: Nur die <mark> Tags entfernen und Text wieder zusammenfügen,
    // anstatt das komplette innerHTML zu überschreiben (was Event-Listener und Animationen zerstört).
    const clearSearch = () => {
        searchableBlocks.forEach(block => {
            const marks = block.querySelectorAll('mark');
            marks.forEach(mark => {
                const parent = mark.parentNode;
                if (parent) {
                    // Ersetzt <mark>Suchbegriff</mark> durch einen simplen Textknoten
                    parent.replaceChild(document.createTextNode(mark.textContent), mark);
                    parent.normalize(); // Fasst zersplitterte Textknoten wieder sauber zusammen
                }
            });
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
        
        // Sicherstellen, dass ein noch unsichtbares (animiertes) Element sofort aufploppt
        const animatedParent = activeMatch.closest('.animated-element');
        if (animatedParent) {
            animatedParent.classList.add('is-visible');
        }

        // Feature: Falls der Treffer in einem geschlossenen Akkordeon liegt, öffne es!
        const accordionContent = activeMatch.closest('.accordion-content');
        if (accordionContent) {
            const header = accordionContent.previousElementSibling;
            if (header && !header.classList.contains('active')) {
                header.classList.add('active');
                accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
            }
        }

        activeMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
        searchCounter.textContent = `${currentIndex + 1} / ${matches.length}`;
    };

    // Escaped Sonderzeichen für reguläre Ausdrücke
    const escapeRegExp = (string) => string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    siteSearchInput.addEventListener('input', () => {
        const query = siteSearchInput.value.trim();
        
        clearSearch();

        if (query.length < 3) return;

        // UX: Mache alle Animationen im Suchbereich sofort sichtbar, damit nichts übersehen wird
        searchableBlocks.forEach(block => {
            const animations = block.querySelectorAll('.animated-element:not(.is-visible)');
            animations.forEach(el => el.classList.add('is-visible'));
        });

        const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');

        searchableBlocks.forEach(block => {
            // TreeWalker durchsucht ausschließlich Text-Nodes, ignoriert DOM-Struktur komplett
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
                
                if (textNode.parentNode) {
                    textNode.parentNode.replaceChild(fragment, textNode);
                }
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