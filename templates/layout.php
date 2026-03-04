<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'SAP Academy') ?></title>
    <!-- Wir laden hier die modulare main.css aus dem public Ordner -->
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="theme-default">
    
    <?php require BASE_PATH . '/templates/partials/header.php'; ?>

    <!-- Der spezifische Seiteninhalt, der vom Controller übergeben wird -->
    <?= $content ?? '' ?>

    <?php require BASE_PATH . '/templates/partials/footer.php'; ?>

    <!-- JS-Logik modular geladen -->
    <script src="/js/main.js" type="module"></script>
</body>
</html>