<?php

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    abstract class AbstractController {
        
        /**
         * Méthode permettant de charger une vue.
         *
         * @param string $name Nom de la vue
         * @param array $data Données à passer à la vue
         */        
        public function view($name, $data = []){
            if (!empty($data) && is_array($data)) { // Si des données sont passées à la vue et que ce sont des tableaux
                extract($data, EXTR_SKIP); // Extraction des variables du tableau
            }
            
            $filename = "../app/views/" . $name . ".view.php"; // On récupère le nom du fichier
    
            if(file_exists($filename)){
                require $filename;
            } else {
                require "../app/views/404.view.php";
            }
        }
    }