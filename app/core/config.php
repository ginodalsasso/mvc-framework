<?php
    defined('ROOTPATH') OR exit("Access Denied!");

    if($_SERVER["SERVER_NAME"] == "localhost") {  // Si le serveur est en local
        // database configuration
        define ('DBNAME', 'my_db');
        define ('DBHOST', 'localhost');
        define ('DBUSER', 'root');
        define ('DBPASS', '');

        // root url
        define ('ROOT', 'http://localhost/mvc-framework/public'); 
    } else { // Si le serveur est en ligne
        // database configuration
        define ('DBNAME', 'my_db');
        define ('DBHOST', 'localhost');
        define ('DBUSER', 'root');
        define ('DBPASS', '');

        // root url
        define ('ROOT', 'https://mywebsite.com');
    }

    define('APP_NAME', 'My Website');
    define('APP_DESC', 'Best website ever...');

    // true veut dire que le mode debug est activé
    define('DEBUG', true);