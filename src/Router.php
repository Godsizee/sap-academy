<?php
/**
 * Router Klasse
 * Kümmert sich ausschließlich um das Auflösen der URLs und die Weiterleitung 
 * an den richtigen Controller. (Single Responsibility Prinzip)
 */
class Router {
    public function dispatch($uri) {
        // Query-Parameter ignorieren für das reine Routing (z.B. aus /module?id=s4f10 wird /module)
        $parsedUrl = parse_url($uri);
        $path = $parsedUrl['path'];

        $controller = new Controller();

        // Simples Routing-Konzept (KISS)
        switch ($path) {
            case '/':
            case '/index.php':
                $controller->home();
                break;
            case '/module':
                $moduleId = $_GET['id'] ?? null;
                $controller->showModule($moduleId);
                break;
            case '/quiz':
                $moduleId = $_GET['id'] ?? null;
                $controller->showQuiz($moduleId);
                break;
            case '/quiz-uebersicht':
                $moduleId = $_GET['id'] ?? null;
                $controller->showQuizUebersicht($moduleId);
                break;
            case '/glossar':
                $controller->showGlossary();
                break;
            case '/datenschutz':
                $controller->showDatenschutz();
                break;
            default:
                $controller->notFound();
                break;
        }
    }
}