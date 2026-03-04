/**
 * Navigation Modul
 * Kümmert sich um das Burger-Menü, Dropdowns, Scroll-Spy und die Progress-Bar.
 */

export function initNavigation() {
    // --- Mobile Navigation (Burger Menü) ---
    const burger = document.querySelector('.burger');
    if (burger) {
        burger.addEventListener('click', (e) => {
            e.stopPropagation();
            document.body.classList.toggle('nav-active');
            burger.classList.toggle('toggle');
        });
    }

    // --- Dropdown-Logik ---
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownContent = document.querySelector('.dropdown-content');

    if (dropdownToggle && dropdownContent) {
        dropdownToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownContent.classList.toggle('show');
        });

        // Schließt das Dropdown, wenn außerhalb geklickt wird
        document.addEventListener('click', () => {
            if (dropdownContent.classList.contains('show')) {
                dropdownContent.classList.remove('show');
            }
        });
    }

    // --- Sidebar Scroll-Spy & Progress Bar ---
    const sidebar = document.querySelector('.sidebar-nav');
    const progressBar = document.getElementById('progressBar');
    const backToTopBtn = document.getElementById('backToTopBtn');

    const handleScroll = () => {
        const scrollTop = document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        
        // Progress Bar Update
        if (progressBar) { 
            progressBar.style.width = `${(scrollTop / scrollHeight) * 100}%`; 
        }
        
        // Back to Top Button Toggle
        if (backToTopBtn) {
            if (scrollTop > 300) backToTopBtn.classList.add('show');
            else backToTopBtn.classList.remove('show');
        }
    };

    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    window.addEventListener('scroll', handleScroll);

    // Intersection Observer für das Highlighting der aktuellen Sektion in der Sidebar
    if (sidebar) {
        const sections = document.querySelectorAll('h2[id], h3[id]');
        const navLinks = sidebar.querySelectorAll('ul a');
        
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }, { rootMargin: '-20% 0px -70% 0px' });

        sections.forEach(section => observer.observe(section));
    }
}