<?php 
    defined('ROOTPATH') OR exit("Access Denied!");

    // ici on inclut tous les fichiers nécessaires à l'application
    
    spl_autoload_register(function($classname){ // charge automatiquement les classes qui sont appelées mais qui n'ont pas été incluses
        require $filename = "../app/models/".ucfirst($classname).".php";
    });

    require "config.php";
    require "functions.php";
    require "Database.php";
    require "Model.php";
    require "Controller.php";
    require "App.php";