<?php
    /**
     * Image class
     * Class pour la manipulation des images
     */

    /** 
     * Instanciaition de la classe Image dans le namespace Core 
     * ex: $image = new Core\Image();
     */
    namespace Model; 

    defined('ROOTPATH') OR exit("Access Denied!");

    class Image {

        /**
         * Redimensionne une image pour que sa taille maximale (largeur ou hauteur) corresponde à la taille spécifiée.
         * 
         * @param string $filename Chemin de l'image à redimensionner.
         * @return string Retourne le chemin de l'image redimensionnée.
         */        
        public function resize($filename, $max_size = 700){ // max_size est la taille maximale de l'image en pixels
            // Récupération du type de l'image
            $type = mime_content_type($filename);

           // Vérifie si le fichier image existe avant de poursuivre
            if(file_exists($filename)) {
                // Sélectionne la fonction appropriée pour créer une ressource d'image en fonction du type MIME
                switch($type) {
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($filename);
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng($filename);
                        break;
                    case 'image/gif':
                        $image = imagecreatefromgif($filename);
                        break;
                    case 'image/webp':
                        $image = imagecreatefromwebp($filename);
                        break;
                    default:
                        // Si le type d'image n'est pas supporté, retourne le fichier sans modification
                        return $filename;
                }

                // Obtient les dimensions (largeur et hauteur) de l'image originale
                $src_w = imagesx($image); // Largeur
                $src_h = imagesy($image); // Hauteur

                // Redimensionnement en fonction de l'orientation de l'image (paysage ou portrait)
                if($src_w > $src_h) { // Orientation paysage (largeur > hauteur)
                    if($src_w > $max_size) {
                        $max_size = $src_w;
                    }
                    // Détermine les dimensions cibles pour conserver le rapport d'aspect
                    $dst_w = $max_size; // Largeur de l'image redimensionnée
                    $dst_h = ($src_h / $src_w) * $max_size; // Hauteur proportionnelle à la largeur
                } else { // Orientation portrait (hauteur >= largeur)
                    if($src_h < $max_size) {
                        $max_size = $src_h;
                    }
                    // Détermine les dimensions cibles pour conserver le rapport d'aspect
                    $dst_w = ($src_w / $src_h) * $max_size; // Largeur proportionnelle à la hauteur
                    $dst_h = $max_size; // Hauteur de l'image redimensionnée
                }

                // Arrondit les valeurs calculées 
                $dst_w = round($dst_w); // Largeur
                $dst_h = round($dst_h); // Hauteur

                // Crée une nouvelle image vide avec les dimensions calculées pour la redimension
                $dst_image = imagecreatetruecolor($dst_w, $dst_h);

                // Spécifique aux images PNG : conserve la transparence
                if($type == 'image/png') {
                    imagealphablending($dst_image, false); // Désactive le mélange de l'alpha pour éviter la perte de transparence
                    imagesavealpha($dst_image, true); // Active la sauvegarde de la transparence dans l'image
                }

                // Redimensionne l'image source et copie les pixels dans l'image cible
                imagecopyresampled($dst_image, $image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);

                // Libère la mémoire de l'image source car elle n'est plus nécessaire
                imagedestroy($image);

                // Sauvegarde l'image redimensionnée dans le fichier original en fonction de son type
                switch($type) {
                    case 'image/jpeg':
                        imagejpeg($dst_image, $filename, 100); // Sauvegarde l'image
                        break;
                    case 'image/png':
                        imagepng($dst_image, $filename, 10);
                        break;
                    case 'image/gif':
                        imagegif($dst_image, $filename); 
                        break;
                    case 'image/webp':
                        imagewebp($dst_image, $filename, 100);
                        break;
                    default:
                        imagewebp($dst_image, $filename, 100); // Par défaut, sauvegarde en WebP si le type est inconnu (par sécurité)
                        break;
                }

                // Libère la mémoire de l'image redimensionnée car elle n'est plus nécessaire
                imagedestroy($dst_image);
            }

            // Retourne le chemin du fichier image redimensionnée
            return $filename;
        }

        /**
         * Recadre une image pour qu'elle corresponde à la taille spécifiée.
         * ex:
         * $filename = "path/to/image.jpg";
         * $max_width = 300; // Largeur cible
         * $max_height = 300; // Hauteur cible
         * $croppedImage = $image->crop($filename, $max_width, $max_height);
         * 
         * @param string $filename Chemin de l'image à recadrer.
         * @return string Retourne le chemin de l'image recadrée.
         */
        public function crop($filename, $max_width = 700, $max_height = 700){ // max_size est la taille maximale de l'image en pixels
            // Récupération du type de l'image
            $type = mime_content_type($filename);

           // Vérifie si le fichier image existe avant de poursuivre
            if(file_exists($filename)) {

                $imagefunc = 'imagecreatefromjpeg';

                // Sélectionne la fonction appropriée pour créer une ressource d'image en fonction du type MIME
                switch($type) {
                    case 'image/jpeg':
                        $image = imagecreatefromjpeg($filename);
                        $imagefunc = 'imagecreatefromjpeg';
                        break;
                    case 'image/png':
                        $image = imagecreatefrompng($filename);
                        $imagefunc = 'imagecreatefrompng';
                        break;
                    case 'image/gif':
                        $image = imagecreatefromgif($filename);
                        $imagefunc = 'imagecreatefromgif';
                        break;
                    case 'image/webp':
                        $image = imagecreatefromwebp($filename);
                        $imagefunc = 'imagecreatefromwebp';
                        break;
                    default:
                        // Si le type d'image n'est pas supporté, retourne le fichier sans modification
                        return $filename;
                        break;
                }

                // Obtient les dimensions (largeur et hauteur) de l'image originale
                $src_w = imagesx($image); // Largeur
                $src_h = imagesy($image); // Hauteur

                if($max_width > $max_height) { // Si la largeur est supérieure à la hauteur 
                    
                    if($src_w > $src_h) { // si l'image est en paysage
                        $max = $max_width;
                    } else {
                        $max = ($src_w / $src_h) * $max_width; // Hauteur proportionnelle à la largeur
                    }

                } else {
                    if($src_w > $src_h) { // si l'image est en paysage
                        $max = ($src_w / $src_h) * $max_height; // Largeur proportionnelle à la hauteur
                    } else {
                        $max = $max_height;
                    }
                }

                $this->resize($filename, $max); // Redimensionne l'image

                $image = $imagefunc($filename); // Recrée l'image après redimensionnement
                
                // Obtient les dimensions (largeur et hauteur) de l'image originale
                $src_w = imagesx($image); // Largeur
                $src_h = imagesy($image); // Hauteur

                $src_x = 0; // Coordonnée X de l'image source
                $src_y = 0; // Coordonnée Y de l'image source

                // Calcule les coordonnées de l'image source pour la recadrer
                if($max_width > $max_height) { 
                    $src_y = round(($src_h - $max_height) / 2); // Centre l'image verticalement
                } else {
                    $src_x = round(($src_w - $max_width) / 2); // Centre l'image horizontalement
                }


                // Redimensionnement en fonction de l'orientation de l'image (paysage ou portrait)
                $dst_image = imagecreatetruecolor($max_width, $max_height);

                // Spécifique aux images PNG : conserve la transparence
                if($type == 'image/png') {
                    imagealphablending($dst_image, false); // Désactive le mélange de l'alpha pour éviter la perte de transparence
                    imagesavealpha($dst_image, true); // Active la sauvegarde de la transparence dans l'image
                }

                // Redimensionne l'image source et copie les pixels dans l'image cible
                imagecopyresampled($dst_image, $image, 0, 0, $src_x, $src_y, $max_width, $max_height, $max_width, $max_height);

                // Libère la mémoire de l'image source car elle n'est plus nécessaire
                imagedestroy($image);

                // Sauvegarde l'image redimensionnée dans le fichier original en fonction de son type
                switch($type) {
                    case 'image/jpeg':
                        imagejpeg($dst_image, $filename, 100); // Sauvegarde l'image
                        break;
                    case 'image/png':
                        imagepng($dst_image, $filename, 10);
                        break;
                    case 'image/gif':
                        imagegif($dst_image, $filename); 
                        break;
                    case 'image/webp':
                        imagewebp($dst_image, $filename, 100);
                        break;
                    default:
                        imagewebp($dst_image, $filename, 100); // Par défaut, sauvegarde en WebP si le type est inconnu (par sécurité)
                        break;
                }

                // Libère la mémoire de l'image redimensionnée car elle n'est plus nécessaire
                imagedestroy($dst_image);
            }

            // Retourne le chemin du fichier image redimensionnée
            return $filename;
        }

        /**
         * Crée une miniature de l'image spécifiée.
         * ex:
         * $filename = "path/to/image.jpg";
         * $max_width = 150; // Largeur cible de la miniature
         * $max_height = 150; // Hauteur cible de la miniature
         * $thumbnail = $image->getThumbnail($filename, $max_width, $max_height);
         * 
         * @param string $filename Chemin de l'image pour laquelle créer une miniature.
         * @param int $max_width Largeur maximale de la miniature.
         */
        public function getThumbnail($filename, $max_width = 700, $max_height = 700) {
            
            if(file_exists($filename)) {
                $ext = explode(".", $filename); // Récupère l'extension du fichier
                $ext = end($ext); // Récupère la dernière valeur du tableau
                $dest = preg_replace('/\.'.$ext.'$/', '_thumbnail.'.$ext, $filename); // Ajoute '_thumbnail' au nom du fichier
                
                if(file_exists($dest)) { // Vérifie si la miniature existe 
                    return $dest; // Retourne le chemin de la miniature
                }

                copy($filename, $dest); // Copie l'image dans le fichier de destination
                $this->crop($dest, $max_width, $max_height); // Redimensionne l'image

                $filename = $dest; // Remplace le nom du fichier par celui de la miniature
            }

            return $filename;
        }
    }