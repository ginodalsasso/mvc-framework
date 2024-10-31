<?php

    if($_SERVER["SERVER_NAME"] == "localhost") {
        // database configuration
        define ('DBNAME', 'my_db');
        define ('DBHOST', 'localhost');
        define ('DBUSER', 'root');
        define ('DBPASS', '');

        // root url
        define ('ROOT', 'http://localhost/mvc-framework/public');
    } else {
        // database configuration
        define ('DBNAME', 'my_db');
        define ('DBHOST', 'localhost');
        define ('DBUSER', 'root');
        define ('DBPASS', '');

        // root url
        define ('ROOT', 'https://mywebsite.com');
    }