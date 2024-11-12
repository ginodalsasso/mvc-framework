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


    /**
    * Cette fonction récupère un segment de l'URL en fonction de la clé fournie.
    * L'URL doit être au format 'page/section/action/id'.
    * @return mixed La partie demandée de l'URL, ou null si elle n'existe pas.
     */
    function URL($key):mixed {
        $URL = $_GET['url'] ?? 'home'; // Si 'url' est absent dans la requête, 'home' est utilisé par défaut
        $URL = explode('/', trim($URL,"/")); // Convertit la chaîne en tableau en séparant les éléments par "/"

        switch ($key) {
            case 'page':
            case 0:
                return $URL[0] ?? null;
                break;
            case 'section':
            case 'slug':
            case 1:
                return $URL[1] ?? null;
                break;
            case 'action':
            case 2:
                return $URL[2] ?? null;
                break;
            case 'id':
            case 3:
                return $URL[3] ?? null;
                break;
            default:
                return null;
                break;
        }
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


    /**
     *Fonction pour récupérer les anciennes valeurs type text des champs de formulaire
     *ex: input type="text" value="old_value('name')" 
     */
    function old_value(string $key, mixed $default = "", string $mode = "post"): mixed {

        $POST = ($mode == "post") ? $_POST : $_GET; // Si le mode est post, on récupère les données post, sinon on récupère les données get
        
        if(isset($POST[$key])){ // Si la clé existe dans le tableau, on retourne sa valeur
            return $POST[$key];
        }

        return $default; // Sinon on retourne la valeur par défaut
    }


    /**
    * Fonction pour récupérer les anciennes valeurs type radio des champs de formulaire
    * ex: input type="radio" checked
    */
    function old_checked(string $key, string $value, string $default = ""): string {

        if(isset($_POST[$key])){ // Si la clé existe dans le tableau
            if($_POST[$key] == $value){ // Si la valeur de la clé est égale à la valeur passée en paramètre
                return " checked ";
            }
        } else {
            if($_SERVER['REQUEST_METHOD'] == "GET" && $default == $value){ // Si la méthode est GET et la valeur par défaut est égale à la valeur passée en paramètre
                return " checked ";
            }
        }

        return ""; // Sinon on retourne une chaîne vide
    }


    /**
     *Fonction pour récupérer les anciennes valeurs type select des champs de formulaire
     *ex: select name="country" option value="old_select('country', 'France')"
     */
    function old_select(string $key, mixed $value, mixed $default = "", string $mode = "post"): string {

        $POST = ($mode == "post") ? $_POST : $_GET; // Si le mode est post, on récupère les données post, sinon on récupère les données get

        if(isset($POST[$key])){ // Si la clé existe dans le tableau
            if($POST[$key] == $value){ // Si la valeur de la clé est égale à la valeur passée en paramètre
                return " selected ";
            }
        } else {
            if($default == $value){ // Si la valeur par défaut est égale à la valeur passée en paramètre
                return " selected ";
            }
        }

        return ""; // Sinon on retourne une chaîne vide
    }


    /**
     * Cette fonction prend une date en entrée et retourne la date formatée
     * au format "jour/mois/année".
     */
    function get_date($date): string {
        return date("j/m/Y", strtotime($date));
    }


    /** 
     * Remplace les images encodées en base64 dans un contenu HTML par des fichiers image locaux, 
     * en les enregistrant dans un dossier spécifique et en les redimensionnant
     */
    function remove_images_from_content($content, $folder = "uploads/"){
        if (!file_exists($folder)) { 
            mkdir($folder, 0777, true);
            file_put_contents($folder . ".htaccess", "Deny from all"); // Crée un fichier .htaccess pour interdire l'accès au dossier
        }
    
        // Recherche toutes les balises <img> dans le contenu
        preg_match_all('/<img[^>]+>/i', $content, $imageTags); // équivalent à <img[^>]+src="([^"]+)"
        $updatedContent = $content; // Stocke le contenu initial dans une nouvelle variable pour modification
    
        // Si des balises <img> sont trouvées dans le contenu
        if (is_array($imageTags) && count($imageTags) > 0) {
            $imageProcessor = new \Model\Image(); // Initialise l'instance de la classe Image
    
            // Parcourt chaque balise <img> trouvée
            foreach ($imageTags[0] as $imageTag) {
                // Ignore les images avec un lien externe (http ou https)
                if (strstr($imageTag, "http")) { 
                    continue;
                }
    
                // Extrait l'attribut src de la balise pour obtenir le chemin de l'image encodée
                preg_match('/src="([^"]+)"/', $imageTag, $srcAttribute);
                // Extrait l'attribut data-filename pour obtenir le nom de l'image
                preg_match('/data-filename="([^"]+)"/', $imageTag, $filenameAttribute);
    
                // Vérifie si l'image est encodée en base64 (présence de 'data' dans l'URL)
                if (strstr($srcAttribute[0], 'data')) { 
                    $encodedParts = explode(',', $srcAttribute[0]); // Sépare la balise src pour extraire les données encodées en base64
                    $baseFilename = $filenameAttribute[1] ?? "default_image.jpg"; // Attribue un nom par défaut si l'attribut data-filename est absent
                    $uniqueFilename = $folder . "img_" . time() . "_" . $baseFilename; // Crée un nom de fichier unique pour éviter les conflits
    
                    // Remplace l'ancien chemin par le nouveau chemin du fichier image dans le contenu
                    $updatedContent = str_replace($encodedParts[0] . ',' . $encodedParts[1], 'src="' . $uniqueFilename . '"', $updatedContent);
                    file_put_contents($uniqueFilename, base64_decode($encodedParts[1])); // Crée le fichier image décodé dans le dossier
    
                    // Redimensionne l'image pour éviter les tailles excessives
                    $imageProcessor->resize($uniqueFilename, 800, 600);
                }
            }
        }
        return $updatedContent; // Retourne le contenu mis à jour avec les nouvelles références d'image   
    }


    /**
     * Supprime les images locales référencées dans le contenu HTML initial, qui ne sont plus présentes
     * dans le contenu mis à jour ou supprime toutes les images si aucun contenu mis à jour n'est fourni.
     */
    function delete_images_from_content(string $content, string $content_new = ""): void {
        if (empty($content_new)) {
            // Récupère toutes les balises <img> dans le contenu original
            preg_match_all('/<img[^>]+>/i', $content, $imageTags); // équivalent à <img[^>]+src="([^"]+)"
            
            // Supprime chaque image locale trouvée dans le contenu original
            if (is_array($imageTags) && count($imageTags) > 0) {
                foreach ($imageTags[0] as $imageTag) {
                    preg_match('/src="([^"]+)"/', $imageTag, $srcAttribute);
                    $filename = str_replace('src="', "", $srcAttribute[0]);

                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                }
            }
        } else {
            // Récupère les balises <img> dans le contenu initial et le contenu mis à jour
            preg_match_all('/<img[^>]+>/i', $content, $imageTags);
            preg_match_all('/<img[^>]+>/i', $content_new, $imageTags_new);
            
            $old_images = []; // Chemins des images dans le contenu initial
            $new_images = []; // Chemins des images dans le contenu mis à jour

            // Stocke les chemins des images trouvées dans le contenu initial
            if (is_array($imageTags) && count($imageTags) > 0) {
                foreach ($imageTags[0] as $imageTag) {
                    preg_match('/src="([^"]+)"/', $imageTag, $srcAttribute);
                    $filename = str_replace('src="', "", $srcAttribute[0]);
                    $old_images[] = $filename;
                }
            }
            // Stocke les chemins des images trouvées dans le contenu mis à jour
            if (is_array($imageTags_new) && count($imageTags_new) > 0) {
                foreach ($imageTags_new[0] as $imageTag) {
                    preg_match('/src="([^"]+)"/', $imageTag, $srcAttribute);
                    $filename = str_replace('src="', "", $srcAttribute[0]);
                    $new_images[] = $filename;
                }
            }
            // Supprime les images locales présentes dans le contenu initial mais absentes du contenu mis à jour
            foreach ($old_images as $old_image) {
                if (!in_array($old_image, $new_images) && file_exists($old_image)) {
                    unlink($old_image);
                }
            }
        }
    }

    
    /**
     * Converti le chemin relatif de l'image en chemin absolu en ajoutant la racine du site.
     */
    function add_root_to_images($contents) {
        // Récupère toutes les balises <img> dans le contenu
        preg_match_all('/<img[^>]+>/i', $contents, $images);
        
        if (is_array($images) && count($images) > 0) {
            foreach ($images[0] as $image) {
                // Extrait l'attribut src de chaque image
                preg_match('/src="([^"]+)"/', $image, $src);

                // Ajoute ROOT au chemin de l'image si ce n'est pas un lien externe
                if (!strstr($src[0], 'http')) {
                    $contents = str_replace($src[0], 'src="' . ROOT . '/' . str_replace('src="', "", $src[0]), $contents);
                }
            }
        }
        return $contents;
    }