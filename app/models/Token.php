<?php
/**
 * Token class
 * Lis et écrit les données de Token
 */

    /** 
     * Instanciaition de la classe Token dans le namespace Core 
     * ex: $token = new Core\Token();
     */
    namespace Model;

    use Model\Model;

    defined('ROOTPATH') OR exit("Access Denied!");

    class Token {

        use Model;

        // déterminer la table à utiliser
        protected $table = "user_session_token";


        public function generateToken(): string {
            $token = bin2hex(random_bytes(32)); 
            return $token;
        }


        public function storeToken($additionalData): void {
            $token = $this->generateToken();
            // $ip_address = $_SERVER['REMOTE_ADDR'];
            $created_at = date('Y-m-d H:i:s');
            $updated_at = $created_at;
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expiration dans 1 heure

            // Préparer les données à insérer
            $data = [
                'token' => $token,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
                'expires_at' => $expires_at,
            ] + $additionalData; // ex: ['user_id' => 1]

            $this->insert($data);
        }


        public function revokeToken(int $userId): void {
            // Préparer les données pour invalider le token
            $data = [
                'expires_at' => date('Y-m-d H:i:s'), // Marquer comme expiré
            ];
        
            // Appeler la méthode update pour marquer tous les tokens de cet utilisateur comme expirés
            $this->update($userId, $data, 'user_id');
        }
    }