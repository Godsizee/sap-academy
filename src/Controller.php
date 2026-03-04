<?php
/**
 * Base Controller
 * Ist das Bindeglied zwischen den Daten (später aus JSON) und den Templates.
 * Bereitet die Daten auf und injiziert sie in das zentrale HTML-Layout.
 */
class Controller {
    
    /**
     * Lädt das Hauptlayout und injiziert den spezifischen Seiteninhalt (View)
     */
    private function render($view, $data = []) {
        // Daten entpacken, damit sie im Template als Variablen verfügbar sind
        extract($data);
        
        // Den Inhalt der spezifischen Unterseite in den Ausgabe-Buffer laden
        ob_start();
        $viewPath = BASE_PATH . "/templates/pages/{$view}.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h2>System-Hinweis: View '{$view}' muss noch erstellt werden.</h2>";
        }
        $content = ob_get_clean();

        // Hauptlayout (Header, Footer, Sidebar) laden und den $content injizieren
        $layoutPath = BASE_PATH . "/templates/layout.php";
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            // Fallback-Ausgabe, solange das Layout noch nicht existiert
            echo $content;
        }
    }

    public function home() {
        $this->render('home', [
            'pageTitle' => 'Willkommen | SAP Academy',
            'pageHeadline' => 'Willkommen in der SAP Academy'
        ]);
    }

    public function showModule($moduleId) {
        if (!$moduleId) {
            $this->notFound();
            return;
        }

        // Später laden wir hier dynamisch die Daten aus s4f10.json oder s4550.json
        $moduleName = strtoupper($moduleId);
        
        $this->render('module', [
            'pageTitle' => "{$moduleName} | SAP Academy",
            'pageHeadline' => "Kurs: {$moduleName}",
            'moduleId' => $moduleId
        ]);
    }

    public function showQuiz($moduleId) {
        if (!$moduleId) {
            $this->notFound();
            return;
        }

        $this->render('quiz', [
            'pageTitle' => "Quiz " . strtoupper($moduleId),
            'moduleId' => $moduleId
        ]);
    }

    public function showGlossary() {
        $this->render('glossary', [
            'pageTitle' => 'Glossar | SAP Academy'
        ]);
    }
        public function showDatenschutz() {
        $this->render('datenschutz', [
            'pageTitle' => 'Datenschutz | SAP Academy'
        ]);
    }

    public function notFound() {
        http_response_code(404);
        echo "<h1>404 - Seite nicht gefunden</h1><p>Gehe zurück zur <a href='/'>Startseite</a>.</p>";
    }
}
