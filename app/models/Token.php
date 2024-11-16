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


        /**
         * Génère un token d'accès
         * @return string
         */
        private function generateToken(): string {
            $token = bin2hex(random_bytes(32)); 
            return $token;
        }


        /**
         * Stocke un token d'accès
         * @param array $additionalData Données supplémentaires à stocker avec le token
         * @return void
         * 
         */
        public function storeToken($additionalData): void {
            // Vérifier si l'ID de l'utilisateur est valide et numérique
            if (!isset($additionalData['user_id']) || !is_numeric($additionalData['user_id'])) {
                throw new \InvalidArgumentException("Invalid user ID provided.");
            }

            $token = $this->generateToken();

            $ip_address = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
            if (!$ip_address) {
                throw new \Exception("IP adress is invalid");
            }

            $created_at = date('Y-m-d H:i:s');
            $updated_at = $created_at;
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expiration +1 heure

            // Préparer les données à insérer
            $data = [
                'token' => $token,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
                'expires_at' => $expires_at,
                'ip_address' => $ip_address,
            ] + $additionalData; // ex: ['user_id' => 1]

            $userId = $additionalData['user_id'];
            $existingToken  = $this->findOneBy(['user_id' => $userId]);

            if (!$existingToken || !isset($existingToken->expires_at)) {
                // Si aucun token n'existe ou si l'attribut expires_at est manquant, insérer un nouveau token
                $this->insert($data);
            } elseif ($this->isTokenExpired($existingToken->expires_at)) {
                // Si le token a expiré, mettre à jour l'existant
                $this->update($userId, $data, 'user_id');
            }
        }


        /**
         * Révoque un token d'accès
         * @param int $userId ID de l'utilisateur
         */
        public function revokeToken(int $userId): void {
            $this->update($userId, ['expires_at' => date('Y-m-d H:i:s')], 'user_id');
        }


        /**
         * Vérifie si un token est valide
         * @param string $token Token à vérifier
         * @return bool retourne true si le token existe et n'a pas expiré
         */
        public function isTokenValid(string $token): bool {
            $existingToken = $this->findOneBy(['token' => $token]);
            return $existingToken !== null && !$this->isTokenExpired($existingToken->expires_at); 
        }
    

        /**
         * Vérifie si un token a expiré
         * @param string $expiresAt Date d'expiration du token
         * @return bool retourne true si le token a expiré
         */
        private function isTokenExpired(?string $expiresAt): bool {
            return empty($expiresAt) || strtotime($expiresAt) < time();
        }
    }