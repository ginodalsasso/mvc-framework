<?php

    session_start();

    require "../app/core/init.php";

    // On définit le mode debug en fonction de la constante DEBUG
    if(DEBUG) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
    
    $app = new App(); // On instancie la classe App
    $app->loadController(); // On charge le contrôleur