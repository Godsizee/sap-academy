<main>
    <section class="hero searchable-block">
        <div class="hero-text">
            <h1><?= htmlspecialchars($pageHeadline) ?></h1>
            <p>Deine moderne, modulare Lernplattform. Refaktorisiert nach KISS, SRP und OCP.</p>
            <a href="#kurse" class="cta-button">Entdecke die Kurse</a>
        </div>
    </section>

 
        <h2>Unsere aktuellen Lernmodule</h2>
        <p style="text-align: center; max-width: 800px; margin: 0 auto 3rem auto; font-size: 1.1rem;">
            Wir haben den Fokus geschärft. Hier findest du unsere beiden hochspezialisierten SAP-Module.
        </p>
        <div class="card-container">
            <div class="card animated-element">
                <h3>S4F10</h3>
                <h4>Finanzbuchhaltung</h4>
                <p>Lerne die Kernprozesse der SAP Finanzbuchhaltung im S/4HANA Umfeld kennen (Universal Journal, GL, AP, AR).</p>
                <a href="<?= BASE_URL ?>/module?id=s4f10">Kurs starten →</a>
            </div>
            <div class="card animated-element">
                <h3>S4550</h3>
                <h4>SAP Customizing</h4>
                <p>Meistere das SAP Customizing. Lerne, wie du das System an die spezifischen Unternehmensanforderungen im Einkauf anpasst.</p>
                <a href="<?= BASE_URL ?>/module?id=s4550">Kurs starten →</a>
            </div>
        </div>
</main>