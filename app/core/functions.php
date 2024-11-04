<?php
    defined('ROOTPATH') OR exit("Access Denied!");

    // Fonction pour afficher les données sous forme de tableau
    function show($stuff) {
        echo "<pre>";
        print_r($stuff);
        echo "</pre>";
    }

    // Fonction pour échapper les caractères spéciaux
    function esc($string) {
        return htmlspecialchars($string);
    }

    function redirect($path){
        header("Location: ".ROOT."/".$path);
        die;
    }
