<?php
    defined('ROOTPATH') OR exit("Access Denied!");

    check_extensions();

    // Fonction pour vérifier les extensions requises
    function check_extensions() {
        // extensions actives du fichier php.ini
        $required_extensions = [
            'gd',
            'curl',
            'fileinfo',
            'intl',
            'mysqli',
        ];

        $not_loaded = [];

        foreach($required_extensions as $extension) {
            if(!extension_loaded($extension)) {
                $not_loaded[] = $extension;
            }
        }

        if(!empty($not_loaded)) {
            show("Please load the following extensions in your php.ini file: <br>" . implode("<br>", $not_loaded));
            die;
        }
    }

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
