<?php

    Trait Controller {
        
        // Méthode permettant de charger une vue
        public function view($name){
            $filename = "../app/views/" . $name . ".view.php"; // On récupère le nom du fichier
    
            if(file_exists($filename)){
                require $filename;
            } else {
                require "../app/views/404.view.php";
            }
        }
    }