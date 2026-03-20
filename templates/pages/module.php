<?php
/**
 * Module View
 * Lädt die JSON-Datei basierend auf der $moduleId und rendert das Lernmodul.
 */

// Pfad zur JSON-Datei generieren
$jsonPath = BASE_PATH . "/data/modules/" . strtolower($moduleId) . ".json";

if (!file_exists($jsonPath)) {
    echo "<main><section class='content-section'><div class='content-page'><h2>Fehler 404</h2><p>Die Inhalte für das Modul <strong>" . htmlspecialchars(strtoupper($moduleId)) . "</strong> konnten nicht gefunden werden.</p></div></section></main>";
    return;
}

// JSON decodieren
$moduleData = json_decode(file_get_contents($jsonPath), true);
?>

<!-- Modernes 2-Spalten-Layout mit CSS Grid -->
<main style="display: grid; grid-template-columns: minmax(280px, 320px) minmax(0, 1fr); gap: 3rem; max-width: 1600px; margin: 0 auto; padding: 3rem 5%; align-items: start;">
    
    <!-- Sidebar-Navigation (Inhaltsverzeichnis) - Links, Sticky -->
    <aside class="sidebar-nav hide-on-mobile" style="position: sticky; top: 100px; height: max-content; max-height: calc(100vh - 120px); overflow-y: auto; padding-right: 0.5rem;">
        <div style="background: var(--bg-light); border: 1px solid var(--border-color); border-radius: 24px; padding: 2rem 1.5rem; box-shadow: 0 15px 35px var(--shadow-color);">
            <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem; color: var(--primary-color); display: flex; align-items: center; gap: 0.5rem;">
                <span>📑</span> Inhaltsverzeichnis
            </h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <?php foreach ($moduleData['chapters'] as $chapter): ?>
                    <li style="margin-bottom: 1.5rem;">
                        <div class="chapter-title" style="font-weight: 700; color: var(--text-color); margin-bottom: 0.8rem; font-size: 1.05rem; line-height: 1.3;">
                            <?= htmlspecialchars($chapter['title']) ?>
                        </div>
                        <ul class="lektion-list" style="list-style: none; padding-left: 1rem; border-left: 2px solid var(--border-color); margin: 0;">
                            <?php foreach ($chapter['lessons'] as $lesson): ?>
                                <li style="margin-bottom: 0.6rem; position: relative;">
                                    <!-- Kleiner Indikator-Punkt für die Ästhetik -->
                                    <span style="position: absolute; left: -1.25rem; top: 0.5rem; width: 6px; height: 6px; border-radius: 50%; background: var(--border-color); transition: background 0.3s, transform 0.3s;"></span>
                                    <a href="#<?= htmlspecialchars($lesson['id']) ?>" style="font-size: 0.95rem; color: var(--text-color); opacity: 0.7; text-decoration: none; display: block; line-height: 1.4; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.color='var(--brand-secondary)';" onmouseout="if(!this.classList.contains('active')){ this.style.opacity='0.7'; this.style.color='var(--text-color)'; }">
                                        <?= htmlspecialchars($lesson['title']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
                
                <li style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <div class="chapter-title" style="font-weight: 700; color: var(--primary-color); margin-bottom: 1rem; font-size: 1.1rem;">Wissenstest</div>
                    <a href="/quiz?id=<?= htmlspecialchars($moduleId) ?>" class="cta-button" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; text-align: center; padding: 0.8rem 1rem; font-size: 1rem; width: 100%; border-radius: 12px;">
                        <span>🎯</span> Zum Quiz wechseln
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Hauptinhalt (Lektionen) - Rechts -->
    <section class="main-column" style="min-width: 0;">
        <div class="content-page learning-content searchable-block" style="padding: 0;">
            
            <div class="breadcrumb hide-on-mobile" style="margin-bottom: 2rem; font-size: 0.9rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">
                <a href="/" style="color: var(--text-color); opacity: 0.6;">Startseite</a> 
                <span style="opacity: 0.3; margin: 0 0.5rem;">/</span> 
                <a href="#" style="color: var(--text-color); opacity: 0.6;">Module</a> 
                <span style="opacity: 0.3; margin: 0 0.5rem;">/</span> 
                <span style="color: var(--brand-primary);"><?= htmlspecialchars(strtoupper($moduleId)) ?></span>
            </div>

            <!-- Fabulous Hero Header für das Modul -->
            <div class="overview-hero-block animated-element is-visible" style="text-align: left; margin-bottom: 4rem; padding: 4rem 3rem; background: var(--bg-light); border-radius: 30px; border: 1px solid var(--border-color); box-shadow: 0 20px 40px var(--shadow-color); position: relative; overflow: hidden;">
                <!-- Dekorativer Background Blob -->
                <div style="position: absolute; top: -20%; right: -10%; width: 50%; height: 150%; background: radial-gradient(circle, var(--brand-primary) 0%, transparent 70%); opacity: 0.05; pointer-events: none;"></div>
                
                <span class="hero-badge" style="display: inline-block; padding: 8px 16px; border-radius: 50px; background: rgba(57, 0, 153, 0.1); color: var(--brand-primary); font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 1.5rem; border: none;">
                    📖 Lernmodul
                </span>
                
                <h1 style="font-size: clamp(2rem, 5vw, 3.5rem); margin-bottom: 1.5rem; color: var(--primary-color); line-height: 1.1; font-family: var(--font-display);">
                    <?= htmlspecialchars($moduleData['headline']) ?>
                </h1>
                
                <p class="lead" style="font-size: 1.2rem; opacity: 0.8; max-width: 800px; line-height: 1.7; color: var(--text-color); margin: 0;">
                    <?= htmlspecialchars($moduleData['description']) ?>
                </p>
            </div>

            <!-- Kapitel & Lektionen Iteration -->
            <?php foreach ($moduleData['chapters'] as $chapter): ?>
                <div class="chapter-block" style="margin-bottom: 5rem;">
                    
                    <!-- Stylische Kapitel Überschrift (Extrahiert automatisch die Nummer) -->
                    <h2 id="<?= htmlspecialchars($chapter['id']) ?>" style="color: var(--text-color); margin-bottom: 2.5rem; font-size: var(--fs-800); display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 2px solid rgba(0,0,0,0.05);">
                        <span style="display: flex; align-items: center; justify-content: center; width: 55px; height: 55px; background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); color: #fff; border-radius: 16px; font-size: 1.6rem; font-weight: bold; flex-shrink: 0; box-shadow: 0 10px 20px rgba(57,0,153,0.2);">
                            <?= preg_replace('/[^0-9]/', '', htmlspecialchars($chapter['title'])) ?: '📄' ?>
                        </span>
                        <span style="line-height: 1.2;"><?= preg_replace('/Kapitel \d+:\s*/', '', htmlspecialchars($chapter['title'])) ?></span>
                    </h2>
                    
                    <!-- Lektionen als Fabulous Cards -->
                    <div style="display: flex; flex-direction: column; gap: 2.5rem;">
                        <?php foreach ($chapter['lessons'] as $lesson): ?>
                            <div class="lesson-block fabulous-card animated-element" id="<?= htmlspecialchars($lesson['id']) ?>" style="text-align: left; padding: 3.5rem 3rem; border-radius: 24px; background: var(--bg-light); border: 1px solid var(--border-color); box-shadow: 0 10px 30px var(--shadow-color); position: relative; transition: transform 0.4s ease, box-shadow 0.4s ease;">
                                
                                <h3 style="font-size: 1.6rem; margin-bottom: 2rem; color: var(--brand-secondary); display: flex; align-items: center; gap: 0.8rem;">
                                    <span style="color: var(--brand-primary); opacity: 0.5; font-size: 1.8rem;">#</span> 
                                    <?= htmlspecialchars($lesson['title']) ?>
                                </h3>
                                
                                <div class="lesson-content" style="font-size: 1.1rem; line-height: 1.8; color: var(--text-color); opacity: 0.9;">
                                    <!-- Inhalt wird als HTML ausgegeben -->
                                    <?= $lesson['content'] ?>
                                </div>
                                
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Quiz Call-to-Action -->
            <div class="fabulous-card animated-element" style="text-align: center; margin-top: 6rem; padding: 5rem 3rem; background: linear-gradient(135deg, rgba(57,0,153,0.05), rgba(158,0,89,0.05)); border: 2px dashed var(--brand-primary); border-radius: 30px;">
                <div style="font-size: 4.5rem; margin-bottom: 1.5rem; animation: pulse-arrow 2s infinite;">🚀</div>
                <h3 style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--brand-primary); font-family: var(--font-display);">Wissen überprüfen?</h3>
                <p style="font-size: 1.2rem; opacity: 0.8; margin-bottom: 3rem; max-width: 600px; margin-inline: auto; line-height: 1.6;">
                    Bereit für den nächsten Schritt? Teste dein erlerntes Wissen im interaktiven Quiz und bereite dich optimal auf die Zertifizierung vor.
                </p>
                <a href="/quiz?id=<?= htmlspecialchars($moduleId) ?>" class="cta-button" style="font-size: 1.2rem; padding: 1.2rem 3rem; border-radius: 50px; box-shadow: 0 15px 30px rgba(57, 0, 153, 0.3);">
                    Quiz starten
                </a>
            </div>

        </div>
    </section>

</main>

<!-- JS für intelligentes Scrollverhalten des Inhaltsverzeichnisses (Active States) -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Scroll-Spy Logik
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // 1. Alle Links zurücksetzen
                document.querySelectorAll('.lektion-list a').forEach(link => {
                    link.classList.remove('active');
                    link.style.color = 'var(--text-color)';
                    link.style.opacity = '0.7';
                    link.style.fontWeight = '400';
                    link.previousElementSibling.style.background = 'var(--border-color)';
                    link.previousElementSibling.style.transform = 'scale(1)';
                });
                
                // 2. Aktiven Link hervorheben
                const id = entry.target.getAttribute('id');
                const activeLink = document.querySelector(`.lektion-list a[href="#${id}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                    activeLink.style.color = 'var(--brand-primary)';
                    activeLink.style.opacity = '1';
                    activeLink.style.fontWeight = '700';
                    activeLink.previousElementSibling.style.background = 'var(--brand-primary)';
                    activeLink.previousElementSibling.style.transform = 'scale(1.5)';
                }
            }
        });
    }, { rootMargin: '-20% 0px -70% 0px' }); // Trigger in der oberen Bildschirmhälfte

    // Alle Lektionen überwachen
    document.querySelectorAll('.lesson-block').forEach(block => {
        observer.observe(block);
    });
});
</script>