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
    // ex: message('Votre compte a été créé avec succès', true)
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


    // Fonction pour récupérer les anciennes valeurs type text des champs de formulaire
    // ex: input type="text" value="old_value('name')"
    function old_value(string $key, mixed $default = "", string $mode = "post"): mixed {

        $POST = ($mode == "post") ? $_POST : $_GET; // Si le mode est post, on récupère les données post, sinon on récupère les données get
        
        if(isset($POST[$key])){ // Si la clé existe dans le tableau, on retourne sa valeur
            return $POST[$key];
        }

        return $default; // Sinon on retourne la valeur par défaut
    }


    // Fonction pour récupérer les anciennes valeurs type radio des champs de formulaire
    // ex: input type="radio" checked
    function old_checked(string $key, string $value, string $default = ""): string {

        if(isset($_POST[$key])){ // Si la clé existe dans le tableau
            if($_POST[$key] == $value){ // Si la valeur de la clé est égale à la valeur passée en paramètre
                return "checked";
            }
        } else {
            if($_SERVER['REQUEST_METHOD'] == "GET" && $default == $value){ // Si la méthode est GET et la valeur par défaut est égale à la valeur passée en paramètre
                return "checked";
            }
        }

        return ""; // Sinon on retourne une chaîne vide
    }


    // Fonction pour récupérer les anciennes valeurs type select des champs de formulaire
    // ex: select name="country" option value="old_select('country', 'France')"
    function old_select(string $key, mixed $value, mixed $default = "", string $mode = "post"): string {

        $POST = ($mode == "post") ? $_POST : $_GET; // Si le mode est post, on récupère les données post, sinon on récupère les données get

        if(isset($POST[$key])){ // Si la clé existe dans le tableau
            if($POST[$key] == $value){ // Si la valeur de la clé est égale à la valeur passée en paramètre
                return "selected";
            }
        } else {
            if($default == $value){ // Si la valeur par défaut est égale à la valeur passée en paramètre
                return "selected";
            }
        }

        return ""; // Sinon on retourne une chaîne vide
    }