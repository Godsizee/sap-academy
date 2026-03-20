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

// --- STRUKTUR-OVERRIDE AUS DEN BILDERN (NUR FÜR S4550) ---
// Überschreibt die JSON-Daten dynamisch mit der exakten Struktur aus dem Inhaltsverzeichnis.
// Übungen und Seitenzahlen wurden absichtlich weggelassen.
if (strtolower($moduleId) === 's4550') {
    $moduleData['chapters'] = [
        [
            'id' => 'kapitel-1',
            'title' => '1 Die Unternehmensstruktur im Customizing',
            'lessons' => [
                ['id' => 'l1-1', 'title' => 'Lektion 1: Die Unternehmensstruktur', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>']
            ]
        ],
        [
            'id' => 'kapitel-2',
            'title' => '2 Stammdaten im Customizing',
            'lessons' => [
                ['id' => 'l2-1', 'title' => 'Lektion 1: Systemmeldungen und Materialstammsätze', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>'],
                ['id' => 'l2-2', 'title' => 'Lektion 2: Geschäftspartner', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>']
            ]
        ],
        [
            'id' => 'kapitel-3',
            'title' => '3 Bewertung und Kontenfindung',
            'lessons' => [
                ['id' => 'l3-1', 'title' => 'Lektion 1: Bewertung und Kontenfindung konfigurieren', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>'],
                ['id' => 'l3-2', 'title' => 'Lektion 2: Kontenfindung aufteilen', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>']
            ]
        ],
        [
            'id' => 'kapitel-4',
            'title' => '4 Customizing für den Einkauf',
            'lessons' => [
                ['id' => 'l4-1', 'title' => 'Lektion 1: Belegarten pflegen', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>'],
                ['id' => 'l4-2', 'title' => 'Lektion 2: Die Feldauswahl für Belege festlegen', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>'],
                ['id' => 'l4-3', 'title' => 'Lektion 3: Bestelldruck und Textarten anpassen', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>']
            ]
        ],
        [
            'id' => 'kapitel-5',
            'title' => '5 Bestandsführung',
            'lessons' => [
                ['id' => 'l5-1', 'title' => 'Lektion 1: Systemeinstellungen in der Bestandsführung', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>']
            ]
        ],
        [
            'id' => 'kapitel-6',
            'title' => '6 Anpassungen über das SAP Fiori Launchpad',
            'lessons' => [
                ['id' => 'l6-1', 'title' => 'Lektion 1: Systemanpassungen überprüfen', 'content' => '<p><em>Inhalt für diese Lektion folgt noch...</em></p>']
            ]
        ]
    ];
}
// ---------------------------------------------------------
?>

<main class="page-with-sidebar content-section" style="padding-top: 2rem;">
    
    <!-- Sidebar-Navigation (Inhaltsverzeichnis) -->
    <aside class="sidebar-nav" style="position: sticky; top: 100px; height: max-content; max-height: calc(100vh - 120px); overflow-y: auto; padding-right: 1rem;">
        <div style="background: var(--bg-light); border: 1px solid var(--border-color); border-radius: 20px; padding: 1.5rem; box-shadow: 0 10px 30px var(--shadow-color);">
            <h3 style="font-size: 1.2rem; margin-bottom: 1rem; color: var(--primary-color);">Inhaltsverzeichnis</h3>
            <ul style="list-style: none; padding: 0;">
                <?php foreach ($moduleData['chapters'] as $chapter): ?>
                    <li style="margin-bottom: 1.5rem;">
                        <div class="chapter-title" style="font-weight: 700; color: var(--text-color); margin-bottom: 0.5rem; font-size: 1.05rem;">
                            <?= htmlspecialchars($chapter['title']) ?>
                        </div>
                        <ul class="lektion-list" style="list-style: none; padding-left: 1rem; border-left: 2px solid var(--border-color);">
                            <?php foreach ($chapter['lessons'] as $lesson): ?>
                                <li style="margin-bottom: 0.5rem;">
                                    <a href="#<?= htmlspecialchars($lesson['id']) ?>" style="font-size: 0.95rem; opacity: 0.8; transition: opacity 0.3s, color 0.3s;">
                                        <?= htmlspecialchars($lesson['title']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
                
                <li style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <div class="chapter-title" style="font-weight: 700; color: var(--primary-color); margin-bottom: 0.8rem;">Wissenstest</div>
                    <ul class="lektion-list" style="list-style: none; padding: 0;">
                        <li>
                            <a href="/quiz?id=<?= htmlspecialchars($moduleId) ?>" class="cta-button" style="display: block; text-align: center; padding: 0.6rem 1rem; font-size: 0.95rem;">
                                🎯 Zum Quiz wechseln
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Hauptinhalt (Lektionen) -->
    <section class="main-column">
        <div class="content-page learning-content searchable-block">
            
            <div class="breadcrumb hide-on-mobile" style="margin-bottom: 2rem;">
                <a href="/">Startseite</a> <span style="opacity: 0.5;">/</span> 
                <a href="#">Module</a> <span style="opacity: 0.5;">/</span> 
                <span style="color: var(--primary-color); font-weight: 600;"><?= htmlspecialchars(strtoupper($moduleId)) ?></span>
            </div>

            <!-- Fabulous Hero Header für das Modul -->
            <div class="overview-hero-block animated-element is-visible" style="text-align: center; margin-bottom: 4rem; padding: 3rem 1.5rem; background: rgba(0,0,0,0.02); border-radius: 24px; border: 1px solid var(--border-color); position: relative; overflow: hidden;">
                <div style="position: absolute; top: -50%; left: 50%; transform: translateX(-50%); width: 100%; height: 100%; background: radial-gradient(circle, var(--brand-primary) 0%, transparent 70%); opacity: 0.05; pointer-events: none;"></div>
                
                <span class="hero-badge" style="border-color: var(--brand-primary); color: var(--brand-primary); background: transparent; margin-bottom: 1.5rem;">
                    📖 Lernmodul
                </span>
                
                <h1 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-bottom: 1rem; color: var(--primary-color); line-height: 1.2;">
                    <?= htmlspecialchars($moduleData['headline']) ?>
                </h1>
                
                <p class="lead" style="font-size: 1.15rem; opacity: 0.85; max-width: 700px; margin: 0 auto; line-height: 1.6; color: var(--text-color);">
                    <?= htmlspecialchars($moduleData['description']) ?>
                </p>
            </div>

            <!-- Kapitel & Lektionen Iteration -->
            <?php foreach ($moduleData['chapters'] as $chapter): ?>
                <div class="chapter-block" style="margin-bottom: 4rem;">
                    
                    <!-- Kapitel Überschrift -->
                    <h2 id="<?= htmlspecialchars($chapter['id']) ?>" style="color: var(--brand-primary); margin-bottom: 2rem; font-size: var(--fs-800); display: flex; align-items: center; gap: 1rem;">
                        <span style="display: inline-block; width: 40px; height: 4px; background: var(--brand-secondary); border-radius: 2px;"></span>
                        <?= htmlspecialchars($chapter['title']) ?>
                    </h2>
                    
                    <!-- Lektionen als Fabulous Cards -->
                    <div style="display: flex; flex-direction: column; gap: 2rem;">
                        <?php foreach ($chapter['lessons'] as $lesson): ?>
                            <div class="lesson-block fabulous-card animated-element" id="<?= htmlspecialchars($lesson['id']) ?>" style="text-align: left; padding: 2.5rem; border-radius: 20px;">
                                <h3 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--brand-secondary); border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                                    <?= htmlspecialchars($lesson['title']) ?>
                                </h3>
                                
                                <div class="lesson-content" style="font-size: 1.05rem; line-height: 1.7;">
                                    <!-- Inhalt wird als HTML ausgegeben, da das JSON formatiertes HTML enthält -->
                                    <?= $lesson['content'] ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Quiz Call-to-Action -->
            <div class="fabulous-card animated-element" style="text-align: center; margin-top: 5rem; padding: 4rem 2rem; background: linear-gradient(135deg, rgba(57,0,153,0.05), rgba(158,0,89,0.05)); border: 2px dashed var(--brand-primary);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🚀</div>
                <h3 style="font-size: 2rem; margin-bottom: 1rem; color: var(--brand-primary);">Bereit, dein Wissen zu testen?</h3>
                <p style="font-size: 1.1rem; opacity: 0.8; margin-bottom: 2rem; max-width: 500px; margin-inline: auto;">
                    Überprüfe das Gelernte in unserem interaktiven Quiz und festige dein Wissen für die Zertifizierung.
                </p>
                <a href="/quiz?id=<?= htmlspecialchars($moduleId) ?>" class="cta-button" style="font-size: 1.1rem; padding: 1rem 2.5rem;">
                    Quiz zu <?= htmlspecialchars(strtoupper($moduleId)) ?> starten
                </a>
            </div>

        </div>
    </section>

</main>