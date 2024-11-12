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


            // Retourne la valeur d'une clé dans les données GET ex: $request->get('id')
            public function get(string $key = '', mixed $default = ''): mixed {

                if(empty($key)) { // Si la clé est vide on retourne toutes les données GET

                    return $_GET; 
                }else if(isset($_GET[$key])) { // Si la clé existe dans les données GET retournons sa valeur

                    return $_GET[$key];
                }
                return $default; // On retourne la valeur par défaut si la clé n'existe pas
            }


            // Retourne la valeur d'une clé dans les données POST ex: $request->post('email')
            public function post(string $key = '', mixed $default = ''): mixed {

                if(empty($key)) { // Si la clé est vide on retourne toutes les données POST

                    return $_POST; 
                }else if(isset($_POST[$key])) { // Si la clé existe dans les données POST retournons sa valeur

                    return $_POST[$key];
                }
                return $default; // On retourne la valeur par défaut si la clé n'existe pas
            }


            // Retourne la valeur d'une clé dans les données FILES ex: $request->files('avatar')
            public function files(string $key = '', mixed $default = ''): mixed {

                if(empty($key)) { // Si la clé est vide on retourne toutes les données FILES

                    return $_FILES; 
                }else if(isset($_FILES[$key])) { // Si la clé existe dans les données FILES retournons sa valeur

                    return $_FILES[$key];
                }
                return $default; // On retourne la valeur par défaut si la clé n'existe pas
            }


            // Retourne la valeur d'une clé dans les données REQUEST ex: $request->input('email')
            public function input(string $key, mixed $default = ''): mixed {
                
                if(isset($_REQUEST[$key])) { // Si la clé existe dans les données REQUEST retournons sa valeur

                    return $_REQUEST[$key];
                }
                return $default; // On retourne la valeur par défaut si la clé n'existe pas
            }

            public function all():array {
                return $_REQUEST;
            }
        }