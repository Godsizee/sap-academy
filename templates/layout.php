<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'SAP Academy') ?></title>
    
    <link rel="stylesheet" href="/css/modules/_variables.css">
    <link rel="stylesheet" href="/css/modules/_fonts.css">
    <link rel="stylesheet" href="/css/modules/_base.css">
    <link rel="stylesheet" href="/css/modules/_layout.css">
    <link rel="stylesheet" href="/css/modules/_components.css">
    <link rel="stylesheet" href="/css/modules/_utilities.css">
    <link rel="stylesheet" href="/css/modules/_modes.css">
    <link rel="stylesheet" href="/css/modules/_responsive.css">
    <link rel="stylesheet" href="/css/modules/_animations.css">

    <script>
        (function() {
            const savedTheme = localStorage.getItem('selectedTheme') || 'default';
            const savedMode = localStorage.getItem('themeMode') || 'default';
            
            // Theme CSS synchron injizieren
            document.write('<link rel="stylesheet" id="dynamic-theme" href="/css/themes/' + savedTheme + '.css">');
            
            // Klassen temporär auf dem <html> Tag speichern
            document.documentElement.className = 'theme-' + savedTheme + (savedMode !== 'default' ? ' ' + savedMode + '-mode' : '');
        })();
    </script>
</head>
<body>
    <script>
        // Vorbereitete Klassen auf den <body> übertragen, sobald er existiert
        document.body.className = document.documentElement.className;
        document.documentElement.className = '';
    </script>

    <?php require BASE_PATH . '/templates/partials/header.php'; ?>

    <?= $content ?? '' ?>

    <?php require BASE_PATH . '/templates/partials/footer.php'; ?>

    <script src="/js/main.js" type="module"></script>
</body>
</html>