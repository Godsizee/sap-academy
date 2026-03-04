<header>
    <nav>
        <a href="<?= BASE_URL ?>/" class="logo">SAP Academy 🦄</a>

        <div class="nav-cluster-right">
            <div class="dropdown">
                <button class="dropdown-toggle">Lernmodule ▼</button>
                <div class="dropdown-content">
                    <!-- Navigationspunkte zu unseren neuen Modulen -->
                    <a href="<?= BASE_URL ?>/module?id=s4f10">S4F10 - Finanzbuchhaltung</a>
                    <a href="<?= BASE_URL ?>/module?id=s4550">S4550 - Customizing</a>
                    <hr>
                    <a href="<?= BASE_URL ?>/glossar">Glossar</a>
                    <hr>
                    <div class="dropdown-quiz-header">Quiz Sektion</div>
                    <div class="dropdown-quiz-links">
                        <a href="<?= BASE_URL ?>/quiz?id=s4f10">» S4F10 - Quiz</a>
                        <a href="<?= BASE_URL ?>/quiz?id=s4550">» S4550 - Quiz</a>
                    </div>
                </div>
            </div>
            <div class="header-search">
                <input type="search" id="siteSearchInput" placeholder="Seite durchsuchen...">
                <div class="search-controls">
                    <button id="searchPrev" title="Vorheriges Ergebnis">‹</button>
                    <span id="searchCounter">0 / 0</span>
                    <button id="searchNext" title="Nächstes Ergebnis">›</button>
                </div>
            </div>
            <div id="theme-toggle" class="theme-toggle-container">
                <div class="toggle-button">
                    <div class="toggle-circle"></div>
                </div>
            </div>
        </div>

        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </nav>
</header>