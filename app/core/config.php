<?php
    defined('ROOTPATH') OR exit("Access Denied!");

    if((empty($_SERVER["SERVER_NAME"]) && php_sapi_name() == 'cli') || (!empty($_SERVER["SERVER_NAME"]) && $_SERVER["SERVER_NAME"] == "localhost")) {  
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