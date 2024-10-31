<?php

class App {

    private $controller = "Home";
    private $method = "index";

    private function splitURL() {
        $URL = $_GET['url'] ?? 'home'; // S'il n'y a pas de paramètre dans l'url, on affiche la page d'accueil
        $URL = explode('/', $URL); // On transforme l'url en tableau
        return $URL;
    }

    public function loadController() {
        $URL = $this->splitURL();

        $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php"; // On récupère le nom du fichier

        if (file_exists($filename)) {
            require $filename;
            $this->controller = ucfirst($URL[0]); // On met à jour le nom du contrôleur
        } else {
            $filename = "../app/controllers/" . ucfirst($URL[0]) . "/" . ucfirst($URL[0]) . ".php"; // On récupère le nom du fichier

            if (file_exists($filename)) {
                require $filename;
                $this->controller = ucfirst($URL[0]); // On met à jour le nom du contrôleur
            } else {
                $this->controller = "_404"; 
            }
        }

        $controller = new $this->controller; // On instancie le contrôleur
        call_user_func_array([$controller, $this->method], []); // Appel dynamique de la méthode
    }
}
