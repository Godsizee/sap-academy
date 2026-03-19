<?php
/**
 * Quiz Übersicht View
 * Läd die Quiz-Daten aus dem JSON und stellt sie sortiert und aufbereitet als Referenz dar.
 */

$jsonPath = BASE_PATH . "/data/modules/" . strtolower($moduleId) . ".json";

if (!file_exists($jsonPath)) {
    echo "<main><section class='content-section'><div class='content-page'><h2>Fehler 404</h2><p>Die Inhalte für das Modul <strong>" . htmlspecialchars(strtoupper($moduleId)) . "</strong> konnten nicht gefunden werden.</p></div></section></main>";
    return;
}

$moduleData = json_decode(file_get_contents($jsonPath), true);
$quizData = $moduleData['quiz'] ?? [];

// Gruppierung der Fragen nach Kapiteln (Daten-Aufbereitung)
$chapters = [];
foreach ($quizData as $question) {
    $chapters[$question['chapter']][] = $question;
}

// Kapitel alphabetisch/nummerisch sortieren
ksort($chapters);
?>

<main>
    <section class="content-section">
        <div class="content-page searchable-block">
            
<div class="breadcrumb" style="margin-bottom: 2rem;">
                <a href="/">Startseite</a> <span style="opacity: 0.5;">/</span> 
                <a href="/module?id=<?= htmlspecialchars($moduleId) ?>">Modul <?= htmlspecialchars(strtoupper($moduleId)) ?></a> <span style="opacity: 0.5;">/</span> 
                <span style="color: var(--primary-color); font-weight: 600;">Quiz-Übersicht</span>
            </div>

            <div style="text-align: center; margin-bottom: 4rem; padding: 3rem 1.5rem; background: rgba(0,0,0,0.02); border-radius: 24px; border: 1px solid var(--border-color); position: relative; overflow: hidden;">
                <div style="position: absolute; top: -50%; left: 50%; transform: translateX(-50%); width: 100%; height: 100%; background: radial-gradient(circle, var(--brand-primary) 0%, transparent 70%); opacity: 0.05; pointer-events: none;"></div>
                
                <span class="hero-badge" style="border-color: var(--brand-secondary); color: var(--brand-secondary); background: transparent; margin-bottom: 1.5rem;">
                    📚 Referenz-Modus
                </span>
                
                <h1 style="font-size: clamp(1.8rem, 4vw, 2.5rem); margin-bottom: 1rem; color: var(--primary-color); line-height: 1.2;">
                    <?= htmlspecialchars($moduleData['headline']) ?>
                </h1>
                
                <p style="font-size: 1.15rem; opacity: 0.85; max-width: 650px; margin: 0 auto; line-height: 1.6; color: var(--text-color);">
                    Nutze diese Übersicht als dein <strong>persönliches Nachschlagewerk</strong>. 
                    Über das Suchfeld oben rechts kannst du blitzschnell gezielt nach Begriffen, Konzepten oder SAP-Transaktionen filtern.
                </p>
            </div>

            <?php if (empty($quizData)): ?>
                <div class="fabulous-card">
                    <p>Für dieses Modul sind noch keine Quizfragen hinterlegt.</p>
                </div>
            <?php else: ?>

                <?php foreach ($chapters as $chapterName => $questions): ?>
                    <h2 style="color: var(--primary-color); margin-top: 3rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--border-color);">
                        <?= htmlspecialchars($chapterName) ?>
                    </h2>
                    
                    <div class="card-container" style="display: flex; flex-direction: column; gap: 2rem;">
                        <?php foreach ($questions as $index => $q): ?>
                            <div class="fabulous-card animated-element" style="text-align: left; padding: 2rem;">
                                
                                <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem; line-height: 1.4; color: var(--text-color);">
                                    <span style="color: var(--primary-color); margin-right: 0.5rem;">Frage <?= $index + 1 ?>:</span> 
                                    <?= htmlspecialchars($q['question']) ?>
                                </h3>

                                <div class="options" style="display: flex; flex-direction: column; gap: 0.8rem;">
                                    
                                    <?php if ($q['type'] === 'radio' || $q['type'] === 'checkbox'): ?>
                                        <?php
                                        // Antworten extrahieren und garantieren, dass es ein Array ist
                                        $correctAnswers = is_array($q['correct']) ? $q['correct'] : [$q['correct']];
                                        
                                        foreach ($q['options'] as $key => $text):
                                            $isCorrect = in_array($key, $correctAnswers);
                                            $bgColor = $isCorrect ? 'rgba(40, 167, 69, 0.1)' : 'rgba(0, 0, 0, 0.03)';
                                            $borderColor = $isCorrect ? 'var(--brand-success)' : 'transparent';
                                            $opacity = $isCorrect ? '1' : '0.5';
                                        ?>
                                            <div style="padding: 1rem; border-radius: 8px; border-left: 4px solid <?= $borderColor ?>; background-color: <?= $bgColor ?>; opacity: <?= $opacity ?>; transition: opacity 0.3s; color: var(--text-color);">
                                                <strong style="margin-right: 0.5rem;"><?= htmlspecialchars($key) ?>:</strong> 
                                                <?= htmlspecialchars($text) ?>
                                                <?php if($isCorrect): ?>
                                                    <span style="float: right; font-weight: bold; color: var(--brand-success);">✅ Richtig</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php elseif ($q['type'] === 'matching'): ?>
                                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                                            <?php foreach ($q['options']['responses'] as $response): 
                                                $correctStemId = $q['correct'][$response['id']];
                                                $correctStemText = '';
                                                foreach ($q['options']['stems'] as $stem) {
                                                    if ($stem['id'] === $correctStemId) {
                                                        $correctStemText = $stem['text'];
                                                        break;
                                                    }
                                                }
                                            ?>
                                                <div style="padding: 1rem; background: rgba(40, 167, 69, 0.1); border-left: 4px solid var(--brand-success); border-radius: 8px;">
                                                    <strong style="display: block; margin-bottom: 0.5rem; color: var(--text-color);"><?= htmlspecialchars($response['text']) ?></strong>
                                                    <span style="color: var(--brand-success); font-weight: 600;">
                                                        ➡️ <?= htmlspecialchars($correctStemText) ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                    <?php elseif ($q['type'] === 'ordering'): ?>
                                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                            <?php foreach ($q['correct'] as $stepIndex => $correctId):
                                                $correctText = '';
                                                foreach ($q['options'] as $opt) {
                                                    if ($opt['id'] === $correctId) {
                                                        $correctText = $opt['text'];
                                                        break;
                                                    }
                                                }
                                            ?>
                                                <div style="padding: 1rem; background: rgba(40, 167, 69, 0.1); border-left: 4px solid var(--brand-success); border-radius: 8px; display: flex; align-items: center; gap: 1rem;">
                                                    <span style="background: var(--brand-success); color: #fff; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; font-size: 0.8rem;">
                                                        <?= $stepIndex + 1 ?>
                                                    </span>
                                                    <span style="font-weight: 600; color: var(--brand-success);">
                                                        <?= htmlspecialchars($correctText) ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </section>
</main>