<?php

    session_start();

    require "../app/core/init.php";
    
    $app = new App(); // On instancie la classe App
    $app->loadController(); // On charge le contrôleur