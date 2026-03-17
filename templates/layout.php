<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'SAP Academy') ?></title>
    
    <!-- Haupt-CSS importiert alle Module inklusive der neuen Variablen -->
    <link rel="stylesheet" href="/css/main.css">

    <!-- Anti-Flash-Script: Setzt die Klasse vor dem ersten Rendern -->
    <script>
        (function() {
            const savedMode = localStorage.getItem('themeMode') || 'default';
            if (savedMode !== 'default') {
                document.documentElement.classList.add(savedMode + '-mode');
            }
        })();
    </script>
</head>
<body>
    <script>
        /**
         * Da wir im Head nur das <html> Tag mit Klassen versehen konnten,
         * übertragen wir diese beim Laden auf den <body> für konsistentes Styling.
         */
        document.body.className = document.documentElement.className;
        document.documentElement.className = '';
    </script>

    <?php 
    // Header einbinden (Modularer Teil)
    require BASE_PATH . '/templates/partials/header.php'; 
    ?>

    <!-- Zentraler Inhaltsbereich -->
    <div id="app-content">
        <?= $content ?? '' ?>
    </div>

    <?php 
    // Footer einbinden (Modularer Teil)
    require BASE_PATH . '/templates/partials/footer.php'; 
    ?>

    <!-- Haupt-Logik als Modul -->
    <script src="/js/main.js" type="module"></script>
</body>
</html>