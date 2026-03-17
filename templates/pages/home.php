<main>
    <section class="hero">
        <!-- Faszinierende Hintergrund-Animation -->
        <div class="hero-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
        </div>

        <div class="hero-text animated-element is-visible">
            <span class="hero-badge">✨ Fabulous Edition</span>
            <h1 class="hero-title"><?= htmlspecialchars($pageHeadline) ?></h1>
            <p class="hero-subtitle">
                Erlebe die Zukunft des Lernens. Interaktiv, blitzschnell und in einem atemberaubenden Design, das dich fokussiert hält.
            </p>
            <div class="hero-actions">
                <a href="#kurse" class="cta-button">Jetzt Kurse entdecken</a>
            </div>
        </div>
    </section>

    <section id="kurse" class="content-section">
        <div class="section-header">
            <h2>Unsere Lernmodule</h2>
            <p>Hochspezialisierte SAP-Kurse, visuell auf den Punkt gebracht.</p>
        </div>
        
        <div class="card-container">
            <!-- S4F10 Card -->
            <div class="card fabulous-card animated-element is-visible">
                <div class="card-icon">📊</div>
                <h3>S4F10</h3>
                <h4>Finanzbuchhaltung</h4>
                <p>Lerne die Kernprozesse der SAP Finanzbuchhaltung im S/4HANA Umfeld kennen (Universal Journal, GL, AP, AR).</p>
                <a href="<?= BASE_URL ?>/module?id=s4f10" class="card-link">Kurs starten <span class="arrow">→</span></a>
            </div>
            
            <!-- S4550 Card -->
            <div class="card fabulous-card animated-element is-visible">
                <div class="card-icon">⚙️</div>
                <h3>S4550</h3>
                <h4>SAP Customizing</h4>
                <p>Meistere das SAP Customizing. Lerne, wie du das System an die spezifischen Unternehmensanforderungen im Einkauf anpasst.</p>
                <a href="<?= BASE_URL ?>/module?id=s4550" class="card-link">Kurs starten <span class="arrow">→</span></a>
            </div>
        </div>
    </section>
</main>