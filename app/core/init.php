<?php 
    defined('ROOTPATH') OR exit("Access Denied!");

    // ici on inclut tous les fichiers nécessaires à l'application
    
    spl_autoload_register(function($classname){ // charge automatiquement les classes qui sont appelées mais qui n'ont pas été incluses
        $classname = explode("\\", $classname); // on découpe le nom de la classe en tableau pour récupérer le dernier élément
        $classname = end($classname); // on récupère le dernier élément du tableau
        require $filename = "../app/models/".ucfirst($classname).".php";
    });

    require "config.php";
    require "functions.php";
    require "Database.php";
    require "Model.php";
    require "Controller.php";
    require "App.php";