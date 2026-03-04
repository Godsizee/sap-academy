<?php
/**
 * Quiz View
 * Lädt die Quiz-Daten aus der JSON-Datei und baut das HTML-Gerüst für die JS-Engine.
 */

$jsonPath = BASE_PATH . "/data/modules/" . strtolower($moduleId) . ".json";

if (!file_exists($jsonPath)) {
    echo "<section class='content-page'><h2>Fehler: Quizdaten nicht gefunden!</h2></section>";
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

<div class="quiz-container searchable-block">
    <h1>Quiz: <?= htmlspecialchars($moduleData['headline']) ?></h1>

    <?php if (empty($quizData)): ?>
        <p style="text-align: center;">Für dieses Modul sind noch keine Quizfragen hinterlegt.</p>
    <?php else: ?>

        <!-- Auswahlbildschirm -->
        <div id="chapter-selection-screen">
            <h2>Wähle deine Kapitel</h2>
            <p>Klicke auf die Kapitelkarten, die du in dein Quiz aufnehmen möchtest.</p>
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
                <button type="submit" class="submit-btn" style="margin-top: 2.5rem; width: 100%;">Quiz starten</button>
            </form>
        </div>

        <!-- Quiz-Bildschirm -->
        <div id="quiz-screen" style="display: none; flex-direction: column;">
            <div class="quiz-progress-bar-container">
                <div class="quiz-progress-bar" id="quiz-progress-bar-inner"></div>
            </div>
            <div id="quiz-progress-text"></div>
            
            <div class="question-block">
                <p id="question-text"></p>
                <div class="options" id="options-container"></div>
            </div>

            <div id="feedback-area" style="margin-top: 1.5rem;"></div>
            
            <div class="quiz-navigation" style="margin-top: 2rem;">
                <button id="check-answer-btn" class="submit-btn">Antwort prüfen</button>
                <button id="next-question-btn" class="submit-btn" style="display: none;">Nächste Frage</button>
            </div>
        </div>
        
        <!-- Ergebnis-Bildschirm -->
        <div id="quiz-result-screen" class="quiz-result-screen" style="display: none;">
            <h2 id="result-headline"></h2>
            <p id="result-text">Dein Ergebnis: <strong><span id="score-final"></span> von <span id="total-final"></span></strong> Fragen richtig (<span id="percentage-final" class="result-percentage"></span>%).</p>
            
            <div class="quiz-actions">
                <a href="<?= BASE_URL ?>/" class="cta-button">Zur Startseite</a>
                <button id="retry-incorrect-btn" class="submit-btn" style="display: none;">Falsche Fragen wiederholen</button>
                <button id="restart-quiz-btn" class="submit-btn">Neues Quiz starten</button>
            </div>
        </div>

        <!-- Injektion der Daten in das JS-Window-Objekt für die quizEngine.js -->
        <script>
            window.quizData = <?= json_encode($quizData) ?>;
        </script>

    <?php endif; ?>
</div>