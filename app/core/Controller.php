<?php

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    Trait MainController {
        
        // Méthode permettant de charger une vue
        public function view($name, $data = []){
            if(!empty($data))
                extract($data); // On extrait les données pour les rendre accessibles dans la vue
            
            $filename = "../app/views/" . $name . ".view.php"; // On récupère le nom du fichier
    
            if(file_exists($filename)){
                require $filename;
            } else {
                require "../app/views/404.view.php";
            }
        }
    }