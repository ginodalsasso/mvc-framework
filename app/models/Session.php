<?php
/**
 * Session class
 * Lis et écrit les données de session
 */

    /** 
     * Instanciaition de la classe Session dans le namespace Core 
     * ex: $session = new Core\Session();
     */
    namespace Core; 

    defined('ROOTPATH') OR exit("Access Denied!");

    class Session {
        public $mainKey = 'APP'; // Clé principale pour les données de session
        public $userKey = 'USER'; // Clé pour les données de session de l'utilisateur

        // Méthode pour définir une clé et sa valeur dans la session
        private function start_session():int {

            if(session_status() === PHP_SESSION_NONE) { // Si la session n'est pas démarrée
                session_start();
            }
            return 1; // Retourne 1 si la session est démarrée
        }


        // Méthode pour obtenir une valeur de session à partir de sa clé 
        public function set(mixed $keyOrArray, mixed $value = ''): int {

            $this -> start_session(); // Démarrage de la session

            if(is_array($keyOrArray)){ // si $keyOrArray est un tableau
                foreach($keyOrArray as $key => $value) { 
                    $_SESSION[$this->mainKey][$key] = $value; // On définit chaque clé et sa valeur dans la session
                }
                return 1;
            }
            $_SESSION[$this->mainKey][$keyOrArray] = $value; 
            return 1;
        }


        // Méthode pour obtenir une valeur de session à partir de sa clé ex: $session->get('user_id')
        public function get(string $key, mixed $default = ''): mixed {

            $this -> start_session(); // Démarrage de la session

            if(isset($_SESSION[$this->mainKey][$key])) { // Si la clé existe dans la session
                return $_SESSION[$this->mainKey][$key]; // On retourne la valeur de la clé
            }
            return $default; // On retourne la valeur par défaut si la clé n'existe pas
        }


        // Méthode pour obtenir toutes les données de session de l'utilisateur
        public function auth(mixed $user_row):int {

            $this -> start_session(); // Démarrage de la session
            
            $_SESSION[$this->userKey] = $user_row; // On définit les données de l'utilisateur dans la session

            return 0;
        }


        public function logout(): int {
            $this->start_session(); // Démarrage de la session
        
            // Vérifiez si les données utilisateur existent dans la session
            if (!empty($_SESSION[$this->userKey])) {
                $userId = $_SESSION[$this->userKey]->id ?? null; // Récupérer l'ID de l'utilisateur
        
                if ($userId) {
                    // Révoquer le token actif afin qu'il ne puisse plus être utilisé
                    $token = new \Model\Token();
                    $token->revokeToken($userId);
                }
        
                // Supprimer les données de l'utilisateur de la session
                unset($_SESSION[$this->userKey]);
            }
        
            return 1;
        }
        

        // Méthode pour vérifier si l'utilisateur est connecté
        public function is_logged_in(): bool {
            $this->start_session();
        
            if (!empty($_SESSION[$this->userKey])) {
                $userId = $_SESSION[$this->userKey]->id ?? null;
        
                if ($userId) {
                    // Vérifier si le token de session est valide
                    $tokenModel = new \Model\Token();
                    $token = $tokenModel->findOneBy(['user_id' => $userId]);
        
                    if ($token && !$tokenModel->isTokenExpired($token->expires_at)) { 
                        return true; // Token valide
                    }
        
                    // // Si le token est expiré, déconnecter l'utilisateur
                    $this->logout();
                }
            }
            return false;
        }


        // Méthode pour obtenir les données de l'utilisateur connecté 
        // ex : user('user_id') ou user() pour obtenir toutes les données de l'utilisateur
        public function user(string $key = '', mixed $default = ''): mixed {

            $this -> start_session(); // Démarrage de la session

            if(empty($key) && !empty($_SESSION[$this->userKey])) { // Si la clé n'est pas spécifiée et les données de l'utilisateur existent dans la session
                return $_SESSION[$this->userKey]; // On retourne les données de l'utilisateur
            }else {
                if(!empty($_SESSION[$this->userKey] -> $key)) { // Si la clé existe dans les données de l'utilisateur
                    return $_SESSION[$this->userKey] -> $key; // On retourne la valeur de la clé
                }
                return $default; // On retourne la valeur par défaut si la clé n'existe pas
            }
        }


        // Méthode pour supprimer une clé de la session ex: $session->delete('user_id')
        public function pop(string $key, mixed $default = ''):mixed {

            $this -> start_session(); // Démarrage de la session

            if(!empty($_SESSION[$this->mainKey][$key])) { // Si la clé existe dans la session
                $value = $_SESSION[$this->mainKey][$key]; // On stocke la valeur de la clé pour la retourner                
                unset($_SESSION[$this->mainKey][$key]); // On supprime la clé de la session
                return $value; // On retourne la valeur de la clé
            }
        }


        // Méthode pour afficher toutes les données de la session
        public function all(): mixed {

            $this -> start_session(); // Démarrage de la session

            if(isset($_SESSION[$this->mainKey])) { // Si la clé principale existe dans la session
                return $_SESSION[$this->mainKey]; // On retourne toutes les données de la session
            }
            return []; // On retourne un tableau vide
        }
    }
