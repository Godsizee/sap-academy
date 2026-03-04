/**
 * UI Components Modul
 * Kapselt kleine interaktive Elemente wie Popovers, Akkordeons und Scroll-Animationen.
 */

export function initUIComponents() {
    // --- Popover für Diagramme ---
    const popover = document.getElementById('diagramPopover');
    if (popover) {
        document.body.addEventListener('click', e => {
            const popoverTarget = e.target.closest('[data-popover-text]');

            if (popoverTarget) {
                e.stopPropagation();
                const isAlreadyVisible = popover.classList.contains('visible') && popover.currentTarget === popoverTarget;

                if (isAlreadyVisible) {
                    popover.classList.remove('visible');
                    popover.currentTarget = null;
                } else {
                    popover.textContent = popoverTarget.dataset.popoverText;
                    popover.currentTarget = popoverTarget;
                    const rect = popoverTarget.getBoundingClientRect();
                    popover.style.left = `${rect.left + rect.width / 2}px`;
                    popover.style.top = `${window.scrollY + rect.top}px`;
                    popover.classList.add('visible');
                }
            } else if (!popover.contains(e.target)) {
                popover.classList.remove('visible');
                popover.currentTarget = null;
            }
        });
    }

    // --- Akkordeon ---
    const accordionItems = document.querySelectorAll('.accordion-item');
    if (accordionItems.length > 0) {
        accordionItems.forEach(item => {
            const header = item.querySelector('.accordion-header');
            header.addEventListener('click', () => {
                const content = item.querySelector('.accordion-content');
                const isActive = header.classList.contains('active');
                
                // Schließe alle anderen
                accordionItems.forEach(other => {
                    other.querySelector('.accordion-header').classList.remove('active');
                    other.querySelector('.accordion-content').style.maxHeight = null;
                });

                // Öffne das angeklickte
                if (!isActive) {
                    header.classList.add('active');
                    content.style.maxHeight = `${content.scrollHeight}px`;
                }
            });
        });
    }

    // --- Intersection Observer for Scroll Animations ---
    const animatedElements = document.querySelectorAll('.animated-element');
    if (animatedElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target); // Nur einmal animieren
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(el => observer.observe(el));
    }

    // --- Glossar A-Z Filter ---
    const filterBar = document.querySelector('.filter-bar');
    const glossaryItems = document.querySelectorAll('.glossary-item');
    if (filterBar && glossaryItems.length > 0) {
        filterBar.addEventListener('click', (e) => {
            if (e.target.tagName !== 'A') return;
            e.preventDefault();
            
            const selectedLetter = e.target.dataset.letter;
            filterBar.querySelector('.active')?.classList.remove('active');
            e.target.classList.add('active');
            
            glossaryItems.forEach(item => {
                const term = item.dataset.term;
                const firstLetter = term.charAt(0).toUpperCase();
                if (selectedLetter === 'ALL' || selectedLetter === firstLetter) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
}