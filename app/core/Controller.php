<?php

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    abstract class MainController {
        
        /**
         * Méthode permettant de charger une vue.
         *
         * @param string $name Nom de la vue
         * @param array $data Données à passer à la vue
         */        
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