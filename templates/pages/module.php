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

<main class="page-with-sidebar">
    
    <!-- Sidebar-Navigation (Inhaltsverzeichnis) -->
    <aside class="sidebar-nav">
        <h3>Inhaltsverzeichnis</h3>
        <ul>
            <?php foreach ($moduleData['chapters'] as $chapter): ?>
                <li>
                    <div class="chapter-title"><?= htmlspecialchars($chapter['title']) ?></div>
                    <ul class="lektion-list">
                        <?php foreach ($chapter['lessons'] as $lesson): ?>
                            <li><a href="#<?= htmlspecialchars($lesson['id']) ?>"><?= htmlspecialchars($lesson['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
            <li>
                <div class="chapter-title" style="margin-top: 1rem;">Wissenstest</div>
                <ul class="lektion-list">
                    <li><a href="/quiz?id=<?= htmlspecialchars($moduleId) ?>">🎯 Zum Quiz wechseln</a></li>
                </ul>
            </li>
        </ul>
    </aside>

    <!-- Hauptinhalt (Lektionen) -->
    <section class="main-column">
        <div class="content-page learning-content searchable-block">
            
            <div class="breadcrumb">
                <a href="/">Startseite</a> / Module / <?= htmlspecialchars(strtoupper($moduleId)) ?>
            </div>

            <h1><?= htmlspecialchars($moduleData['headline']) ?></h1>
            <p class="lead" style="font-size: 1.15rem; opacity: 0.9; margin-bottom: 2rem;">
                <?= htmlspecialchars($moduleData['description']) ?>
            </p>

            <hr>

            <?php foreach ($moduleData['chapters'] as $chapter): ?>
                <div class="chapter-block">
                    <h2 id="<?= htmlspecialchars($chapter['id']) ?>"><?= htmlspecialchars($chapter['title']) ?></h2>
                    
                    <?php foreach ($chapter['lessons'] as $lesson): ?>
                        <div class="lesson-block" id="<?= htmlspecialchars($lesson['id']) ?>">
                            <h3><?= htmlspecialchars($lesson['title']) ?></h3>
                            <!-- Inhalt wird als HTML ausgegeben, da das JSON formatiertes HTML enthält -->
                            <?= $lesson['content'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            
            <hr>
            
            <div style="text-align: center; padding: 2rem 0;">
                <p style="margin-bottom: 1rem; font-weight: 600;">Bereit, dein Wissen zu testen?</p>
                <a href="/quiz?id=<?= htmlspecialchars($moduleId) ?>" class="cta-button">Quiz zu <?= htmlspecialchars(strtoupper($moduleId)) ?> starten</a>
            </div>

        </div>
    </section>

</main>