<?php

class App {

    private $controller = "Home"; // Contrôleur par défaut
    private $method = "index";    // Méthode par défaut

    /**
     * Sépare l'URL en segments basés sur les slashes "/".
     * @return array Tableau contenant les segments de l'URL
     */
    private function splitURL() {
        $URL = $_GET['url'] ?? 'home'; // Si 'url' est absent dans la requête, 'home' est utilisé par défaut
        $URL = explode('/', $URL); // Convertit la chaîne en tableau en séparant les éléments par "/"
        return $URL;
    }

    /**
     * Charge le contrôleur en fonction de l'URL, ou affiche une page 404 si le contrôleur n'est pas trouvé.
     */
    public function loadController() {
        $URL = $this->splitURL(); // Récupère les segments de l'URL

        // Construit le chemin du fichier du contrôleur
        $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";

        // Vérifie si le fichier du contrôleur existe
        if (file_exists($filename)) {
            require $filename; // Inclut le fichier du contrôleur
            $this->controller = ucfirst($URL[0]); // Définit le nom du contrôleur d'après l'URL
        } else {
            // Si le fichier du contrôleur n'existe pas, cherche dans un sous-dossier
            $filename = "../app/controllers/" . ucfirst($URL[0]) . "/" . ucfirst($URL[0]) . ".php";

            if (file_exists($filename)) {
                require $filename; // Inclut le fichier du contrôleur dans le sous-dossier
                $this->controller = ucfirst($URL[0]); // Définit le nom du contrôleur
            } else {
                // Si aucun fichier n'est trouvé, définit le contrôleur par défaut pour la page 404
                $this->controller = "_404"; 
            }
        }

        // Instancie dynamiquement la classe contrôleur
        $controller = new $this->controller; 

        // Appelle dynamiquement la méthode spécifiée (index par défaut) sur le contrôleur
        call_user_func_array([$controller, $this->method], []); 
    }
}
