<footer>
    <div class="footer-actions">
        <!-- DSGVO-Anpassung: Wording von "Nutzer" auf "Design/Theme" geändert -->
        <a href="#" id="changeThemeBtn">Lern-Design anpassen</a>
        <span class="footer-divider">|</span>
        <a href="/quiz?id=s4f10">S4F10 Quiz-Übersicht</a>
        <span class="footer-divider">|</span>
        <a href="/quiz?id=s4550">S4550 Quiz-Übersicht</a>
        <span class="footer-divider">|</span>
        <a href="/datenschutz">Datenschutz</a>
    </div>
    <p>&copy; <?= date('Y') ?> SAP Academy | Reine Lernplattform ohne User-Tracking.</p>
</footer>

<!-- Theme-Auswahl Modal (rein lokal, speichert nur im Browser) -->
<div class="user-selection-overlay" id="themeSelectionOverlay">
    <div class="user-selection-modal">
        <h2>Wähle dein Design</h2>
        <p>Passe die Optik der Lernplattform an. Diese Einstellung wird rein lokal in deinem Browser gespeichert (keine Cookies, kein Tracking).</p>
        <form id="themeSelectionForm">
            <div class="user-list">
                <div><label><input type="radio" name="theme" value="default" required> Standard-Theme</label></div>
                <?php
                // Die Namen der Themes basieren auf den CSS-Dateien
                $themes = ["Lutz", "Roell", "Wehner", "Cheffchen", "Hinze", "Seitz", "Kowalsky", "Schoelch", "Wolf", "Hoffmann", "Kraft"];
                foreach ($themes as $theme):
                    $themeValue = strtolower($theme);
                ?>
                <div>
                    <label>
                        <input type="radio" name="theme" value="<?= htmlspecialchars($themeValue) ?>" required>
                        <?= htmlspecialchars($theme) ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="submit-btn">Design anwenden</button>
        </form>
    </div>
</div>

<div class="progress-container">
    <div class="progress-bar" id="progressBar"></div>
</div>
<button id="backToTopBtn" class="back-to-top-btn" data-tooltip="Zum Seitenanfang">&#9650;</button>