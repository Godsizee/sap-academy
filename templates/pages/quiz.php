<?php
/**
 * Quiz View
 * Lädt die Quiz-Daten aus der dedizierten _quiz.json oder als Fallback aus der Haupt-JSON.
 */

$baseId = strtolower($moduleId);
$quizJsonPath = BASE_PATH . "/data/modules/" . $baseId . "_quiz.json";
$mainJsonPath = BASE_PATH . "/data/modules/" . $baseId . ".json";

if (file_exists($quizJsonPath)) {
    $jsonPath = $quizJsonPath;
} elseif (file_exists($mainJsonPath)) {
    $jsonPath = $mainJsonPath;
} else {
    echo "<main><section class='content-section'><div class='content-page'><h2>Fehler 404</h2><p>Die Quizdaten konnten nicht gefunden werden.</p></div></section></main>";
    return;
}

$moduleData = json_decode(file_get_contents($jsonPath), true);
$quizData = $moduleData['quiz'] ?? [];

// Einzigartige Kapitel extrahieren für den Auswahlbildschirm
$chapters = [];
foreach ($quizData as $q) {
    if (!in_array($q['chapter'], $chapters)) {
        $chapters[] = $q['chapter'];
    }
}
sort($chapters);
?>

<main>
    <!-- Breadcrumb: ID hinzugefügt, um es während des Quiz per JS auszublenden -->
    <div id="quiz-breadcrumb" class="breadcrumb hide-on-mobile" style="max-width: 867px; margin: 2rem auto 0; padding: 0 1.5rem;">
        <a href="/">Startseite</a> <span style="opacity: 0.5;">/</span> 
        <a href="/module?id=<?= htmlspecialchars($moduleId) ?>">Modul <?= htmlspecialchars(strtoupper($moduleId)) ?></a> <span style="opacity: 0.5;">/</span> 
        <span style="color: var(--primary-color); font-weight: 600;">Wissenstest</span>
    </div>

    <div class="quiz-container searchable-block">
        
        <!-- App-ähnlicher, cleaner Header: ID hinzugefügt für den "Zen Mode" -->
        <div id="quiz-intro-header" style="text-align: center; margin-bottom: 2.5rem; position: relative;">
            <span class="hero-badge" style="border-color: var(--brand-primary); color: var(--brand-primary); background: transparent; margin-bottom: 1rem;">
                🎓 Prüfungs-Modus
            </span>
            <h1 style="font-size: clamp(1.5rem, 5vw, 2.2rem); margin-bottom: 0.5rem; color: var(--text-color); line-height: 1.2;">
                <?= htmlspecialchars($moduleData['headline']) ?>
            </h1>
        </div>

        <?php if (empty($quizData)): ?>
            <div class="fabulous-card">
                <p>Für dieses Modul sind noch keine Quizfragen hinterlegt.</p>
            </div>
        <?php else: ?>

            <!-- AUSWAHLBILDSCHIRM -->
            <div id="chapter-selection-screen">
                <p style="text-align: center; opacity: 0.85; max-width: 550px; margin: 0 auto 2rem; line-height: 1.6; font-size: 1.05rem;">
                    Wähle die Themengebiete aus, die du abfragen möchtest. Das System stellt dir daraus sofort einen individuellen Test zusammen.
                </p>
                <form id="chapter-select-form">
                    <div class="chapter-selection-grid">
                        <?php foreach ($chapters as $chapter): ?>
                            <div class="chapter-card" data-chapter="<?= htmlspecialchars($chapter) ?>">
                                <input type="checkbox" name="chapters" value="<?= htmlspecialchars($chapter) ?>">
                                <div class="chapter-icon">📚</div>
                                <div class="chapter-title">Kapitel <?= htmlspecialchars($chapter) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Sticky Action Bar -->
                    <div class="sticky-mobile-action">
                        <button type="submit" class="submit-btn" style="width: 100%;">Quiz starten 🚀</button>
                    </div>
                </form>
            </div>

            <!-- QUIZ-BILDSCHIRM -->
            <div id="quiz-screen" style="display: none; flex-direction: column;">
                
                <!-- Eleganter Fortschrittsbalken -->
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.8rem; font-weight: 800; color: var(--primary-color); text-transform: uppercase; letter-spacing: 1px;">Fortschritt</span>
                    <div id="quiz-progress-text" style="color: var(--text-color); opacity: 0.7; font-size: 0.85rem; font-weight: 700;"></div>
                </div>
                <div class="quiz-progress-bar-container" style="height: 8px; border-radius: 10px; background: rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 2.5rem;">
                    <div class="quiz-progress-bar" id="quiz-progress-bar-inner" style="height: 100%; width: 0%; background: linear-gradient(90deg, var(--brand-primary), var(--brand-secondary)); transition: width 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);"></div>
                </div>
                
                <div class="question-block" style="border: none; margin-bottom: 0; padding-bottom: 0;">
                    <h3 id="question-text" style="font-size: clamp(1.2rem, 4vw, 1.5rem); margin-bottom: 2rem; color: var(--text-color); line-height: 1.4;"></h3>
                    <div class="options" id="options-container" style="display: flex; flex-direction: column; gap: 0.5rem;"></div>
                </div>

                <div id="feedback-area" style="margin-top: 1.5rem;"></div>
                
                <!-- Sticky Action Bar -->
                <div class="sticky-mobile-action" style="margin-top: 2rem;">
                    <button id="check-answer-btn" class="submit-btn">Antwort prüfen</button>
                    <button id="next-question-btn" class="submit-btn" style="display: none; background: var(--brand-success); border-color: var(--brand-success);">Nächste Frage ➡️</button>
                </div>

                <!-- NEUER ABBRUCH-BUTTON (Dezent) -->
                <div style="text-align: center; margin-top: 1.5rem;">
                    <button type="button" 
                            onclick="sessionStorage.removeItem('quizState_' + (new URLSearchParams(window.location.search).get('id') || 'default')); window.location.reload();" 
                            style="background: transparent; border: none; color: var(--text-color); opacity: 0.5; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: underline; text-underline-offset: 4px; transition: all 0.3s ease;" 
                            onmouseover="this.style.opacity='1'; this.style.color='var(--brand-danger)';" 
                            onmouseout="this.style.opacity='0.5'; this.style.color='var(--text-color)';">
                        Quiz abbrechen & Fortschritt verwerfen
                    </button>
                </div>
            </div>
            
            <!-- ERGEBNIS-BILDSCHIRM -->
            <div id="quiz-result-screen" class="quiz-result-screen" style="display: none; text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem; animation: pulse-arrow 2s infinite;">🏆</div>
                <h2 id="result-headline" style="font-size: clamp(1.8rem, 4vw, 2.5rem); margin-bottom: 1rem;"></h2>
                <p id="result-text" style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 3rem;">Dein Ergebnis: <br><strong><span id="score-final" style="font-size: 2rem; color: var(--primary-color);"></span> von <span id="total-final" style="font-size: 2rem;"></span></strong> Fragen richtig (<span id="percentage-final" class="result-percentage"></span>%).</p>
                
                <!-- Sticky Action Bar -->
                <div class="quiz-actions sticky-mobile-action" style="display: flex; flex-direction: column; gap: 1rem;">
                    <button id="retry-incorrect-btn" class="submit-btn" style="display: none; background: var(--brand-warning); border-color: var(--brand-warning); color: #000;">Falsche wiederholen 🔄</button>
                    <button id="restart-quiz-btn" class="submit-btn" style="background: var(--brand-secondary); border-color: var(--brand-secondary);">Neues Quiz starten</button>
                    <a href="<?= BASE_URL ?>/module?id=<?= htmlspecialchars($moduleId) ?>" class="cta-button" style="width: 100%; text-align: center; background: transparent; color: var(--text-color); border-color: var(--border-color);">Beenden & Zurück</a>
                </div>
            </div>

            <!-- Injektion der Daten in das JS-Window-Objekt für die quizEngine.js -->
            <script>
                window.quizData = <?= json_encode($quizData) ?>;
            </script>

        <?php endif; ?>
    </div>
</main>