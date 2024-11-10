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
    function esc($string) :string {
        return htmlspecialchars($string);
    }


    function redirect($path){
        header("Location: ".ROOT."/".$path);
        die;
    }


    // Fonction pour afficher les images
    function get_image(mixed $file = '', string $type = 'post'): string {
        $file = $file ?? '';
        if(file_exists($file)){
            return ROOT . '/' . $file;
        } 

        if($type == 'user') {
            return ROOT . '/assets/images/user.webp';
        } else {
            return ROOT . '/assets/images/default.webp';
        }
    }


    // Fonction pour retourner les variables de pagination
    function get_pagination_vars(): array {
        $vars = [];
        $vars['page'] = $_GET['page'] ?? 1; // Si la page n'est pas définie, on affiche la première page
        $vars['page'] = (int)$vars['page']; // On s'assure que la page est un entier
        $vars['prev_page'] = $vars['page'] <= 1 ? 1 : $vars['page'] - 1; // Si la page est inférieure ou égale à 1, on affiche la première page, sinon on affiche la page précédente
        $vars['next_page'] = $vars['page'] + 1; // On affiche la page suivante

        return $vars;
    }


    // Fonction pour afficher les messages à l'utilisateur
    function message(string $msg = null, bool $clear = false) {

        $session = new Core\Session();

        if(!empty($msg)){ // Si le message n'est pas vide on le stocke dans la session
            $session->set('message', $msg);

        }else if(!empty($session->get('message'))){ // Sinon on le récupère
            $msg = $session->get('message');

            if($clear){ // Si clear = true, on supprime le message de la session
                $session->pop('message');
            }
            return $msg;
        }
        return false;
    }