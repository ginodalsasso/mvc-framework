<?php
    /**
     * Request class
     * Lis et écrit les données de la requête POST et GET
     */

        /** 
         * Instanciaition de la classe Request dans le namespace Core 
         * ex: $request = new Core\Request();
         */
        namespace Core; 

        defined('ROOTPATH') OR exit("Access Denied!");

        class Request {

            // Méthode pour obtenir le type de la requête HTTP
            public function method(): string {
                return $_SERVER['REQUEST_METHOD'];
            }


            //Vérifie si la requête contient des données et si c'est une méthode POST
            public function posted():bool {
                if($_SERVER['REQUEST_METHOD'] === 'POST' && count($_POST) > 0) { // Si la méthode de la requête est POST et qu'il y a des données POST
                    return true;
                }
                return false;
            }

            
            // Retourne la valeur d'une clé dans les données POST ex: $request->post('email')
            public function post(string $key, mixed $default = ''): mixed {

                if(empty($key)) { // Si la clé est vide on retourne toutes les données POST

                    return $_POST; 
                }else if(isset($_POST[$key])) { // Si la clé existe dans les données POST retournons sa valeur

                    return $_POST[$key];
                }
                return $default; // On retourne la valeur par défaut si la clé n'existe pas
            }
        }