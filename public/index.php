<?php
// --- SICHERHEIT: Umgebungssteuerung ---
// Setze auf 'production' auf dem Live-Server, um Fehler auszublenden
define('ENVIRONMENT', 'production'); 

if (ENVIRONMENT === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Im Produktivbetrieb keine Fehler an den Browser senden (verhindert Information Disclosure)
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// --- SICHERHEIT: HTTP Security Headers ---
// 1. Verhindert Clickjacking (Seite darf nicht in ein Iframe geladen werden)
header("X-Frame-Options: DENY");
// 2. Verhindert MIME-Type Sniffing (Browser muss dem Content-Type des Servers vertrauen)
header("X-Content-Type-Options: nosniff");
// 3. Aktiviert den XSS-Filter im Browser (Fallback für ältere Browser)
header("X-XSS-Protection: 1; mode=block");
// 4. Strenge Content-Security-Policy (CSP)
// Erlaubt nur Ressourcen (Scripte, Bilder, Fonts) vom EIGENEN Server. Keine externen CDNs erlaubt!
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'; font-src 'self'; img-src 'self' data:; script-src 'self' 'unsafe-inline';");


// --- BASIS-PFADE & URLS ---
// Basis-Pfad definieren
define('BASE_PATH', dirname(__DIR__));

// Dynamische Base-URL ermitteln (Behebt den "Undefined constant"-Fehler!)
$baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', $baseDir === '/' ? '' : $baseDir);


// Simpler Autoloader (KISS)
spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Router initialisieren und Anfrage verarbeiten
$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI']);